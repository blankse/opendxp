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
 * @copyright  Modification Copyright (c) OpenDXP (https://www.opendxp.io)
 * @license    https://www.gnu.org/licenses/gpl-3.0.html  GNU General Public License version 3 (GPLv3)
 */

namespace OpenDxp\Model\Asset\Video\Thumbnail\Config\Listing;

use OpenDxp\Model\Asset\Video\Thumbnail\Config;

/**
 * @internal
 *
 * @property \OpenDxp\Model\Asset\Video\Thumbnail\Config\Listing $model
 */
class Dao extends Config\Dao
{
    public function loadList(): array
    {
        $configs = [];

        foreach ($this->loadIdList() as $name) {
            $configs[] = Config::getByName($name);
        }

        $this->model->setThumbnails($configs);

        return $configs;
    }

    public function getTotalCount(): int
    {
        return count($this->loadIdList());
    }
}
