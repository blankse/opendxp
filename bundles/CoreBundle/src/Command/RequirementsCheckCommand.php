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

namespace OpenDxp\Bundle\CoreBundle\Command;

use OpenDxp\Console\AbstractCommand;
use OpenDxp\Db;
use OpenDxp\Tool\Requirements;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @internal
 */
#[AsCommand(
    name:'opendxp:system:requirements:check',
    description: 'Check system requirements',
    aliases: ['system:requirements:check']
)]
class RequirementsCheckCommand extends AbstractCommand
{
    /** @var int[] $levelsToDisplay */
    protected array $levelsToDisplay = [];

    protected function configure(): void
    {
        $this
            ->addOption('min-level', 'l', InputOption::VALUE_OPTIONAL, "Minimum status level to report: 'warning' or 'error'");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        switch ($input->getOption('min-level')) {
            case 'warning':
            case 'warnings':
                $this->levelsToDisplay = [Requirements\Check::STATE_WARNING, Requirements\Check::STATE_ERROR];

                break;
            case 'error':
            case 'errors':
                $this->levelsToDisplay = [Requirements\Check::STATE_ERROR];

                break;
            default:
                $this->levelsToDisplay = [Requirements\Check::STATE_OK, Requirements\Check::STATE_WARNING, Requirements\Check::STATE_ERROR];

                break;
        }

        $allChecks = Requirements::checkAll(Db::get());

        $this->display($allChecks['checksPHP'], 'PHP');
        $this->display($allChecks['checksMySQL'], 'MySQL');
        $this->display($allChecks['checksFS'], 'Filesystem');
        $this->display($allChecks['checksApps'], 'CLI Tools & Applications');

        return 0;
    }

    /**
     * @param Requirements\Check[] $checks
     */
    protected function display(array $checks, string $title = ''): void
    {
        $checksTab = [];

        foreach ($checks as $check) {
            if (in_array($check->getState(), $this->levelsToDisplay)) {
                $checksTab[] = [$check->getName(), $this->displayState($check->getState())];
            }
        }

        if (!empty($checksTab)) {
            $this->io->table(["<options=bold>$title</>", ''], $checksTab);
        }
    }

    protected function displayState(int $state): string
    {
        switch ($state) {
            case Requirements\Check::STATE_OK:
                $displayState = '<fg=green>ok</>';

                break;
            case Requirements\Check::STATE_WARNING:
                $displayState = '<fg=yellow>warning</>';

                break;
            case Requirements\Check::STATE_ERROR:
            default:
                $displayState = '<fg=red>error</>';

                break;
        }

        return $displayState;
    }
}
