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
 * @copyright  Modification Copyright (c) OpenDXP (https://www.opendxp.io)
 * @license    https://www.gnu.org/licenses/gpl-3.0.html  GNU General Public License version 3 (GPLv3)
 */

namespace OpenDxp\Bundle\GenericExecutionEngineBundle\Repository;

use OpenDxp\Bundle\GenericExecutionEngineBundle\Entity\JobRun;
use OpenDxp\Bundle\GenericExecutionEngineBundle\Entity\JobRunErrorLog;

interface JobRunErrorLogRepositoryInterface
{
    public function createFromJobRun(
        JobRun $jobRun,
        ?int $elementId = null,
        ?string $message = null
    ): void;

    public function update(JobRunErrorLog $jobRunErrorLog): void;

    /**
     * @return JobRunErrorLog[]
     */
    public function getLogsByJobRunId(
        int $jobRunId,
        ?int $step = null,
        array $orderBy = [],
        int $limit = 100,
        int $offset = 0
    ): array;

    public function getTotalCount(): int;

    public function getTotalCountByJobRunId(int $jobRunId): int;
}
