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

namespace OpenDxp\Model\User\Permission\Definition;

use OpenDxp\Model;

/**
 * @method \OpenDxp\Model\User\Permission\Definition\Listing\Dao getDao()
 * @method Model\User\Permission\Definition[] load()
 * @method Model\User\Permission\Definition|false current()
 */
class Listing extends Model\Listing\AbstractListing
{
    /**
     * @param Model\User\Permission\Definition[] $definitions
     *
     * @return $this
     */
    public function setDefinitions(array $definitions): static
    {
        return $this->setData($definitions);
    }

    /**
     * @return Model\User\Permission\Definition[]
     */
    public function getDefinitions(): array
    {
        return $this->getData();
    }
}
