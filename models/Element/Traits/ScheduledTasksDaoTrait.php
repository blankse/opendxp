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

namespace OpenDxp\Model\Element\Traits;

use OpenDxp\Model\Element\Service;

/**
 * @internal
 */
trait ScheduledTasksDaoTrait
{
    /**
     * Deletes all scheduled tasks assigned to the element.
     *
     * @param int[] $ignoreIds
     */
    public function deleteAllTasks(array $ignoreIds = []): void
    {
        $type = Service::getElementType($this->model);
        if ($this->model->getId()) {
            $where = '`cid` = ' . $this->model->getId() . ' AND `ctype` = ' . $this->db->quote($type);
            if ($ignoreIds) {
                $where .= ' AND `id` NOT IN (' . implode(',', $ignoreIds) . ')';
            }
            $this->db->executeStatement('DELETE FROM schedule_tasks WHERE ' . $where);
        }
    }
}
