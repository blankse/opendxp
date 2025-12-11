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

namespace OpenDxp\Bundle\StaticRoutesBundle\Model\Staticroute;

use OpenDxp\Bundle\StaticRoutesBundle\Model\Staticroute;
use OpenDxp\Model\AbstractModel;
use OpenDxp\Model\Listing\CallableFilterListingInterface;
use OpenDxp\Model\Listing\CallableOrderListingInterface;
use OpenDxp\Model\Listing\Traits\FilterListingTrait;
use OpenDxp\Model\Listing\Traits\OrderListingTrait;

/**
 * @method Listing\Dao getDao()
 * @method int getTotalCount()
 */
class Listing extends AbstractModel implements CallableFilterListingInterface, CallableOrderListingInterface
{
    use FilterListingTrait;
    use OrderListingTrait;

    /**
     * @var Staticroute[]|null
     */
    protected ?array $routes = null;

    /**
     * @return Staticroute[]
     */
    public function getRoutes(): array
    {
        if ($this->routes === null) {
            $this->getDao()->loadList();
        }

        return $this->routes;
    }

    /**
     * @param Staticroute[]|null $routes
     *
     * @return $this
     */
    public function setRoutes(?array $routes): static
    {
        $this->routes = $routes;

        return $this;
    }

    /**
     * @return Staticroute[]
     */
    public function load(): array
    {
        return $this->getRoutes();
    }
}
