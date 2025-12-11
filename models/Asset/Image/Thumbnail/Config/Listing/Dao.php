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

namespace OpenDxp\Model\Asset\Image\Thumbnail\Config\Listing;

use OpenDxp\Model\Asset\Image\Thumbnail\Config;

/**
 * @internal
 *
 * @property \OpenDxp\Model\Asset\Image\Thumbnail\Config\Listing $model
 */
class Dao extends Config\Dao
{
    public function loadList(): array
    {
        $configs = [];

        foreach ($this->loadIdList() as $name) {
            $configs[] = Config::getByName($name);
        }
        if ($this->model->getFilter()) {
            $configs = array_filter($configs, $this->model->getFilter());
        }
        if ($this->model->getOrder()) {
            usort($configs, $this->model->getOrder());
        }

        $this->model->setThumbnails($configs);

        return $configs;
    }

    public function getTotalCount(): int
    {
        return count($this->loadList());
    }
}
