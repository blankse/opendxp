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

namespace OpenDxp\Model\Asset\Image\Thumbnail\Config;

use OpenDxp\Model;
use OpenDxp\Model\AbstractModel;
use OpenDxp\Model\Listing\CallableFilterListingInterface;
use OpenDxp\Model\Listing\CallableOrderListingInterface;
use OpenDxp\Model\Listing\Traits\FilterListingTrait;
use OpenDxp\Model\Listing\Traits\OrderListingTrait;

/**
 * @method \OpenDxp\Model\Asset\Image\Thumbnail\Config\Listing\Dao getDao()
 */
class Listing extends AbstractModel implements CallableFilterListingInterface, CallableOrderListingInterface
{
    use FilterListingTrait;
    use OrderListingTrait;

    /**
     * @internal
     *
     * @var Model\Asset\Image\Thumbnail\Config[]|null
     */
    protected ?array $thumbnails = null;

    /**
     * @return Model\Asset\Image\Thumbnail\Config[]
     */
    public function getThumbnails(): array
    {
        if ($this->thumbnails === null) {
            $this->getDao()->loadList();
        }

        return $this->thumbnails;
    }

    /**
     * @param Model\Asset\Image\Thumbnail\Config[]|null $thumbnails
     *
     * @return $this
     */
    public function setThumbnails(?array $thumbnails): static
    {
        $this->thumbnails = $thumbnails;

        return $this;
    }

    /**
     * Alias of getThumbnails()
     *
     * @return Model\Asset\Image\Thumbnail\Config[]
     */
    public function load(): array
    {
        return $this->getThumbnails();
    }
}
