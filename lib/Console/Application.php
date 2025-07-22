<?php
declare(strict_types=1);

/**
 * OpenDXP
 *
 * This source file is licensed under the GNU General Public License version 3 (GPLv3).
 *
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) Pimcore GmbH (https://pimcore.com)
 * @copyright  Modification Copyright (c) OpenDXP (https://www.opendxp.ch)
 * @license    https://www.gnu.org/licenses/gpl-3.0.html  GNU General Public License version 3 (GPLv3)
 */

namespace OpenDxp\Console;

use Doctrine\Migrations\Tools\Console\Command\DoctrineCommand;
use OpenDxp;
use OpenDxp\Event\System\ConsoleEvent;
use OpenDxp\Event\SystemEvents;
use OpenDxp\Migrations\FilteredMigrationsRepository;
use OpenDxp\Migrations\FilteredTableMetadataStorage;
use OpenDxp\Tool\Admin;
use OpenDxp\Tool\MaintenanceModeHelperInterface;
use OpenDxp\Version;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LazyCommand;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * The console application
 *
 * @internal
 */
final class Application extends \Symfony\Bundle\FrameworkBundle\Console\Application
{
    /**
     * @internal param string $name The name of the application
     * @internal param string $version The version of the application
     *
     * @api
     */
    public function __construct(KernelInterface $kernel)
    {
        parent::__construct($kernel);

        $this->setName('OpenDXP');
        $this->setVersion(Version::getVersion());

        // we set locale to EN.UTF8 to not getting into UTF-8 issues, eg. when dealing with umlauts & escapeshellarg()
        setlocale(LC_ALL, ['en.utf8', 'en.UTF-8', 'en_US.utf8', 'en_US.UTF-8', 'en_GB.utf8', 'en_GB.UTF-8']);

        // allow to register commands here (e.g. through plugins)
        $dispatcher = OpenDxp::getEventDispatcher();
        $event = new ConsoleEvent($this);
        $dispatcher->dispatch($event, SystemEvents::CONSOLE_INIT);

        $this->setDispatcher($dispatcher);

        $maintenanceModeHelper = $kernel->getContainer()->get(MaintenanceModeHelperInterface::class);
        $dispatcher->addListener(ConsoleEvents::COMMAND, function (ConsoleCommandEvent $event) use ($kernel, $maintenanceModeHelper) {
            // skip if maintenance mode is on and the flag is not set
            if (($maintenanceModeHelper->isActive() || Admin::isInMaintenanceMode()) &&
                !$event->getInput()->getOption('ignore-maintenance-mode')
            ) {
                throw new RuntimeException(
                    'In maintenance mode - set the flag --ignore-maintenance-mode to force execution!'
                );
            }

            if ($event->getInput()->getOption('maintenance-mode')) {
                // enable maintenance mode if requested
                $maintenanceModeId = 'cache-warming-dummy-session-id';

                $event->getOutput()->writeln(
                    'Activating maintenance mode with ID <comment>' . $maintenanceModeId . '</comment> ...'
                );

                $maintenanceModeHelper->activate($maintenanceModeId);
            }

            if ($event->getCommand() instanceof DoctrineCommand &&
                $prefix = $event->getInput()->getOption('prefix')
            ) {
                $kernel->getContainer()->get(FilteredMigrationsRepository::class)->setPrefix($prefix);
                $kernel->getContainer()->get(FilteredTableMetadataStorage::class)->setPrefix($prefix);
            }
        });

        $dispatcher->addListener(ConsoleEvents::TERMINATE, function (ConsoleTerminateEvent $event) use ($maintenanceModeHelper) {
            if ($event->getInput()->getOption('maintenance-mode')) {
                $event->getOutput()->writeln('Deactivating maintenance mode...');
                //BC Layer for Admin::activateMaintenanceMode, if the maintenance file already exists
                if (Admin::isInMaintenanceMode()) {
                    Admin::deactivateMaintenanceMode();
                }
                $maintenanceModeHelper->deactivate();
            }
        });
    }

    /**
     * Gets the default input definition.
     *
     */
    protected function getDefaultInputDefinition(): InputDefinition
    {
        $inputDefinition = parent::getDefaultInputDefinition();
        $inputDefinition->addOption(new InputOption('ignore-maintenance-mode', null, InputOption::VALUE_NONE, 'Set this flag to force execution in maintenance mode'));
        $inputDefinition->addOption(new InputOption('maintenance-mode', null, InputOption::VALUE_NONE, 'Set this flag to force maintenance mode while this task runs'));

        return $inputDefinition;
    }

    public function add(Command $command): ?Command
    {
        if ($command instanceof LazyCommand && str_starts_with($command->getName(), 'doctrine:')) {
            $command = $command->getCommand();
        }

        if ($command instanceof DoctrineCommand) {
            $definition = $command->getDefinition();

            // add filter option
            $definition->addOption(new InputOption(
                'prefix',
                null,
                InputOption::VALUE_OPTIONAL,
                'Optional prefix filter for version classes, eg. OpenDxp\Bundle\CoreBundle\Migrations'
            ));
        }

        return parent::add($command);
    }
}
