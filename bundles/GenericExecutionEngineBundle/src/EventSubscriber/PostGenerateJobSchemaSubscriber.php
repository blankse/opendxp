<?php
declare(strict_types=1);

namespace OpenDxp\Bundle\GenericExecutionEngineBundle\EventSubscriber;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\ORM\Tools\Event\GenerateSchemaTableEventArgs;
use Doctrine\ORM\Tools\ToolEvents;
use OpenDxp\Bundle\GenericExecutionEngineBundle\Entity\JobRun;
use OpenDxp\Bundle\GenericExecutionEngineBundle\Entity\JobRunErrorLog;
use OpenDxp\Bundle\GenericExecutionEngineBundle\Utils\Constants\TableConstants;

#[AsDoctrineListener(event: ToolEvents::postGenerateSchemaTable, connection: 'default')]
final readonly class PostGenerateJobSchemaSubscriber
{
    /**
     * @throws SchemaException
     */
    public function __invoke(GenerateSchemaTableEventArgs $eventArgs): void
    {
        $classMetadata = $eventArgs->getClassMetadata();
        $table = $eventArgs->getClassTable();

        if ($classMetadata->getName() === JobRun::class) {
            $table->addForeignKeyConstraint(
                'users',
                ['ownerId'],
                ['id'],
                ['onDelete' => 'SET NULL'],
                'fk_generic_job_execution_owner_users'
            );
        } elseif ($classMetadata->getName() === JobRunErrorLog::class) {
            $table->addForeignKeyConstraint(
                TableConstants::JOB_RUN_TABLE,
                ['jobRunId'],
                ['id'],
                ['onDelete' => 'CASCADE'],
                'fk_generic_job_execution_log_jobs'
            );
        }
    }
}
