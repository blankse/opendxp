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

namespace OpenDxp\Maintenance;

/**
 * @internal
 */
interface ExecutorInterface
{
    public function executeTask(string $name): void;

    /**
     * Execute the Maintenance Task
     *
     * @param string[] $validJobs
     * @param string[] $excludedJobs
     */
    public function executeMaintenance(array $validJobs = [], array $excludedJobs = []): void;

    public function registerTask(string $name, TaskInterface $task, ?string $messengerMessageClass = null): void;

    public function getTaskNames(): array;

    public function getLastExecution(): int;

    public function setLastExecution(): void;
}
