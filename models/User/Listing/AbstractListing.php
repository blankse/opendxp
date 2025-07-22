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

namespace OpenDxp\Model\User\Listing;

use OpenDxp\Model;

/**
 * @internal
 *
 * @method \OpenDxp\Model\User\Listing\AbstractListing\Dao getDao()
 * @method Model\User[] load()
 * @method Model\User|false current()
 * @method int getTotalCount()
 */
abstract class AbstractListing extends Model\Listing\AbstractListing
{
    protected string $type;

    public function getItems(): array
    {
        return $this->getData();
    }

    /**
     * @return $this
     */
    public function setItems(array $items): static
    {
        return $this->setData($items);
    }

    public function getType(): string
    {
        return $this->type;
    }
}
