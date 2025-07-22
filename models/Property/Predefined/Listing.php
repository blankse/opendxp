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

namespace OpenDxp\Model\Property\Predefined;

use OpenDxp\Model;
use OpenDxp\Model\AbstractModel;
use OpenDxp\Model\Listing\CallableFilterListingInterface;
use OpenDxp\Model\Listing\CallableOrderListingInterface;
use OpenDxp\Model\Listing\Traits\FilterListingTrait;
use OpenDxp\Model\Listing\Traits\OrderListingTrait;

/**
 * @internal
 *
 * @method \OpenDxp\Model\Property\Predefined\Listing\Dao getDao()
 * @method int getTotalCount()
 */
class Listing extends AbstractModel implements CallableFilterListingInterface, CallableOrderListingInterface
{
    use FilterListingTrait;
    use OrderListingTrait;

    protected ?array $properties = null;

    /**
     * @return \OpenDxp\Model\Property\Predefined[]
     */
    public function getProperties(): array
    {
        if ($this->properties === null) {
            $this->getDao()->loadList();
        }

        return $this->properties;
    }

    /**
     * @param \OpenDxp\Model\Property\Predefined[] $properties
     *
     * @return $this
     */
    public function setProperties(array $properties): static
    {
        $this->properties = $properties;

        return $this;
    }

    /**
     * @return Model\Property\Predefined[]
     */
    public function load(): array
    {
        return $this->getProperties();
    }
}
