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

namespace OpenDxp\Bundle\GenericExecutionEngineBundle\Repository;

use Doctrine\DBAL\Exception;
use OpenDxp\Bundle\GenericExecutionEngineBundle\Entity\JobRun;
use OpenDxp\Bundle\GenericExecutionEngineBundle\Exception\JobNotFoundException;
use OpenDxp\Bundle\GenericExecutionEngineBundle\Model\Job;
use OpenDxp\Model\Element\ElementDescriptor;

interface JobRunRepositoryInterface
{
    public function createFromJob(Job $job, int $ownerId = null): JobRun;

    public function update(JobRun $jobRun): JobRun;

    public function updateLogLocalized(
        JobRun $jobRun,
        string $message,
        array $params = [],
        bool $updateCurrentMessage = true,
        string $defaultLocale = 'en'
    ): void;

    /**
     * @throws Exception
     *
     * @internal
     */
    public function updateLogLocalizedWithDomain(
        JobRun $jobRun,
        string $message,
        array $params = [],
        bool $updateCurrentMessage = true,
        string $defaultLocale = 'en',
        string $domain = 'admin'
    ): void;

    /**
     * @throws Exception
     */
    public function updateLog(JobRun $jobRun, string $message): void;

    public function getJobRunById(int $id, bool $forceReload = false, ?int $ownerId = null): JobRun;

    /**
     * @return JobRun[]
     */
    public function getJobRunsByUserId(
        int $ownerId = null,
        array $orderBy = [],
        int $limit = 100,
        int $offset = 0
    ): array;

    public function getTotalCount(): int;

    public function getRunningJobsByUserId(
        int $ownerId,
        array $orderBy = [],
        int $limit = 10,
    ): array;

    public function getLastJobRunByName(string $name): ?JobRun;

    /**
     * @param ElementDescriptor[] $selectedElements
     *
     * @throws JobNotFoundException
     */
    public function updateSelectedElements(JobRun $jobRun, array $selectedElements): void;
}
