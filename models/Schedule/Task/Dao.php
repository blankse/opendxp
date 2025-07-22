<?php

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

namespace OpenDxp\Model\Schedule\Task;

use OpenDxp\Model;

/**
 * @internal
 *
 * @property \OpenDxp\Model\Schedule\Task $model
 */
class Dao extends Model\Dao\AbstractDao
{
    /**
     *
     * @throws Model\Exception\NotFoundException
     */
    public function getById(int $id): void
    {
        $data = $this->db->fetchAssociative('SELECT * FROM schedule_tasks WHERE id = ?', [$id]);
        if (!$data) {
            throw new Model\Exception\NotFoundException('there is no task for the requested id');
        }
        $this->assignVariablesToModel($data);
    }

    public function save(): void
    {
        if (!$this->model->getId()) {
            $this->create();
        }

        $this->update();
    }

    /**
     * Create a new record for the object in database
     */
    public function create(): void
    {
        $this->db->insert('schedule_tasks', []);
        $this->model->setId((int) $this->db->lastInsertId());
    }

    /**
     * Save changes to database, it's an good idea to use save() instead
     */
    public function update(): void
    {
        $site = $this->model->getObjectVars();
        $data = [];

        foreach ($site as $key => $value) {
            if (in_array($key, $this->getValidTableColumns('schedule_tasks'))) {
                if (is_array($value) || is_object($value)) {
                    $value = \OpenDxp\Tool\Serialize::serialize($value);
                } elseif (is_bool($value)) {
                    $value = (int)$value;
                }
                $data[$key] = $value;
            }
        }

        $this->db->update('schedule_tasks', $data, ['id' => $this->model->getId()]);
    }

    /**
     * Deletes object from database
     */
    public function delete(): void
    {
        $this->db->delete('schedule_tasks', ['id' => $this->model->getId()]);
    }
}
