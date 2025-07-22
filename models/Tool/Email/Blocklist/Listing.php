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

namespace OpenDxp\Model\Tool\Email\Blocklist;

use OpenDxp\Model;

/**
 * @internal
 *
 * @method \OpenDxp\Model\Tool\Email\Blocklist\Listing\Dao getDao()
 * @method void delete()*
 * @method Model\Tool\Email\Blocklist[] load()
 * @method Model\Tool\Email\Blocklist|false current()
 * @method int getTotalCount()
 */
class Listing extends Model\Listing\AbstractListing
{
    /**
     * @param Model\Tool\Email\Blocklist[]|null $items
     *
     * @return $this
     */
    public function setItems(?array $items): static
    {
        return $this->setData($items);
    }

    /**
     * @return Model\Tool\Email\Blocklist[]
     */
    public function getItems(): array
    {
        return $this->getData();
    }
}
