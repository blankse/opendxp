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

namespace OpenDxp\Model\Version;

use OpenDxp\Model;

/**
 * @method \OpenDxp\Model\Version\Listing\Dao getDao()
 * @method int[] loadIdList()
 * @method Model\Version[] load()
 * @method Model\Version|false current()
 * @method int getTotalCount()
 */
class Listing extends Model\Listing\AbstractListing
{
    /**
     * @internal
     */
    protected bool $loadAutoSave = false;

    public function isLoadAutoSave(): bool
    {
        return $this->loadAutoSave;
    }

    /**
     * @return $this
     */
    public function setLoadAutoSave(bool $loadAutoSave): static
    {
        $this->loadAutoSave = $loadAutoSave;

        return $this;
    }

    /**
     * @return Model\Version[]
     */
    public function getVersions(): array
    {
        return $this->getData();
    }

    /**
     * @param Model\Version[]|null $versions
     *
     * @return $this
     */
    public function setVersions(?array $versions): static
    {
        return $this->setData($versions);
    }
}
