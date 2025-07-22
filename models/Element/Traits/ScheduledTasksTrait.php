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

namespace OpenDxp\Model\Element\Traits;

use OpenDxp\Model\Element\Service;
use OpenDxp\Model\Schedule\Task;
use OpenDxp\Model\Schedule\Task\Listing;

/**
 * @internal
 */
trait ScheduledTasksTrait
{
    /**
     * Contains all scheduled tasks
     *
     * @var Task[]|null
     */
    protected ?array $scheduledTasks = null;

    /**
     * @return Task[] the $scheduledTasks
     */
    public function getScheduledTasks(): array
    {
        if ($this->scheduledTasks === null) {
            $taskList = new Listing();
            $ctype = Service::getElementType($this);
            $taskList->setCondition('`cid` = ? AND `ctype` = ?', [$this->getId(), $ctype]);

            $this->setScheduledTasks($taskList->load());
        }

        return $this->scheduledTasks;
    }

    /**
     * @param Task[] $scheduledTasks
     *
     * @return $this
     */
    public function setScheduledTasks(array $scheduledTasks): static
    {
        $this->scheduledTasks = $scheduledTasks;

        return $this;
    }

    public function saveScheduledTasks(): void
    {
        $scheduledTasks = $this->getScheduledTasks();
        $ignoreIds = [];
        $ctype = Service::getElementType($this);
        foreach ($scheduledTasks as $task) {
            $task->setDao(null);
            $task->setCid($this->getId());
            $task->setCtype($ctype);
            $task->save();
            $ignoreIds[] = $task->getId();
        }
        $this->getDao()->deleteAllTasks($ignoreIds);
    }
}
