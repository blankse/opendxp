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

namespace OpenDxp\Model\Site;

use OpenDxp\Model;

/**
 * @method \OpenDxp\Model\Site\Listing\Dao getDao()
 * @method Model\Site[] load()
 * @method Model\Site|false current()
 */
class Listing extends Model\Listing\AbstractListing
{
    /**
     * @return Model\Site[]
     */
    public function getSites(): array
    {
        return $this->getData();
    }

    /**
     * @param Model\Site[]|null $sites
     *
     * @return $this
     */
    public function setSites(?array $sites): static
    {
        return $this->setData($sites);
    }
}
