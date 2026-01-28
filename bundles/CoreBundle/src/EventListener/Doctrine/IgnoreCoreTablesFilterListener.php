<?php
declare(strict_types=1);

namespace OpenDxp\Bundle\CoreBundle\EventListener\Doctrine;

use Doctrine\DBAL\Schema\AbstractAsset;
use Doctrine\Migrations\Tools\Console\Command\DoctrineCommand;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

#[AutoconfigureTag('doctrine.dbal.schema_filter')]
class IgnoreCoreTablesFilterListener implements EventSubscriberInterface
{
    private bool $enabled = true;
    protected bool $initialized = false;
    protected array $coreTables;

    public function __construct(protected ManagerRegistry $managerRegistry)
    {
    }

    public function __invoke(AbstractAsset|string $assetName): bool
    {
        if (!$this->enabled) {
            return true;
        }

        if ($assetName instanceof AbstractAsset) {
            $assetName = $assetName->getName();
        }

        $this->loadManagedTables();

        return in_array($assetName, $this->coreTables, true);
    }

    private function loadManagedTables(): void
    {
        if ($this->initialized === true) {
            return;
        }

        $this->initialized = true;
        $this->coreTables = [];

        foreach ($this->managerRegistry->getManagers() as $em) {
            foreach ($em->getMetadataFactory()->getAllMetadata() as $metadata) {
                if ($metadata instanceof ClassMetadata && !in_array($metadata->getTableName(), $this->coreTables, true)) {
                    $this->coreTables[] = $metadata->getTableName();
                }
            }
        }
    }

    public function onConsoleCommand(ConsoleCommandEvent $event): void
    {
        if ($event->getCommand() instanceof DoctrineCommand) {
            $this->enabled = false;
        }
    }

    #[\Override]
    public static function getSubscribedEvents(): array
    {
        return [
            ConsoleEvents::COMMAND => 'onConsoleCommand',
        ];
    }
}
