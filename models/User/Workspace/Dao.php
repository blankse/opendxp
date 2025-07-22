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

namespace OpenDxp\Model\User\Workspace;

use OpenDxp\Db\Helper;
use OpenDxp\Model;
use OpenDxp\Model\User\Workspace;

/**
 * @internal
 *
 * @property Workspace\Asset|Workspace\Document|Workspace\DataObject $model
 */
class Dao extends Model\Dao\AbstractDao
{
    public function save(): void
    {
        $tableName = '';
        if ($this->model instanceof Workspace\Asset) {
            $tableName = 'users_workspaces_asset';
        } elseif ($this->model instanceof Workspace\Document) {
            $tableName = 'users_workspaces_document';
        } elseif ($this->model instanceof Workspace\DataObject) {
            $tableName = 'users_workspaces_object';
        }

        $data = [];

        // add all permissions
        $dataRaw = $this->model->getObjectVars();
        foreach ($dataRaw as $key => $value) {
            if (in_array($key, $this->getValidTableColumns($tableName))) {
                if (is_bool($value)) {
                    $value = (int) $value;
                }

                $data[$key] = $value;
            }
        }
        $this->db->insert($tableName, Helper::quoteDataIdentifiers($this->db, $data));
    }
}
