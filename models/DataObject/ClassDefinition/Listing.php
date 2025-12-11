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

namespace OpenDxp\Model\DataObject\ClassDefinition;

use OpenDxp\Model;

/**
 * @method \OpenDxp\Model\DataObject\ClassDefinition\Listing\Dao getDao()
 * @method Model\DataObject\ClassDefinition[] load()
 * @method Model\DataObject\ClassDefinition|false current()
 */
class Listing extends Model\Listing\AbstractListing
{
    /**
     * @return Model\DataObject\ClassDefinition[]
     */
    public function getClasses(): array
    {
        return $this->getData();
    }

    /**
     * @param Model\DataObject\ClassDefinition[]|null $classes
     *
     * @return $this
     */
    public function setClasses(?array $classes): static
    {
        return $this->setData($classes);
    }
}
