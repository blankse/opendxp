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

namespace OpenDxp\Model\DataObject\SelectOptions\Config\Listing;

use OpenDxp\Model\DataObject\SelectOptions\Config;

/**
 * @internal
 *
 * @property \OpenDxp\Model\DataObject\SelectOptions\Config\Listing $model
 */
class Dao extends Config\Dao
{
    public function loadList(): array
    {
        $configs = [];
        foreach ($this->loadIdListByReadTargets() as $id) {
            $configs[] = Config::getById($id);
        }

        $this->model->setSelectOptions($configs);

        return $configs;
    }

    public function getTotalCount(): int
    {
        return count($this->loadList());
    }
}
