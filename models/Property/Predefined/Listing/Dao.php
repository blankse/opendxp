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

namespace OpenDxp\Model\Property\Predefined\Listing;

use OpenDxp\Model;
use OpenDxp\Model\Property;

/**
 * @internal
 *
 * @property \OpenDxp\Model\Property\Predefined\Listing $model
 */
class Dao extends Model\Property\Predefined\Dao
{
    /**
     * Loads a list of predefined properties for the specicifies parameters, returns an array of Property\Predefined elements
     *
     * @return Model\Property\Predefined[]
     */
    public function loadList(): array
    {
        $properties = [];

        foreach ($this->loadIdList() as $id) {
            $properties[] = Model\Property\Predefined::getById($id);
        }
        if ($this->model->getFilter()) {
            $properties = array_filter($properties, $this->model->getFilter());
        }
        if ($this->model->getOrder()) {
            usort($properties, $this->model->getOrder());
        }

        $this->model->setProperties($properties);

        return $properties;
    }

    public function getTotalCount(): int
    {
        return count($this->loadList());
    }
}
