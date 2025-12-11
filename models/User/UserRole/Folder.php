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

namespace OpenDxp\Model\User\UserRole;

use OpenDxp\Model;
use OpenDxp\Model\User\Role;

/**
 * @method \OpenDxp\Model\User\UserRole\Dao getDao()
 */
class Folder extends Model\User\AbstractUser
{
    /**
     * @internal
     */
    protected ?array $children = null;

    /**
     * @internal
     */
    protected ?bool $hasChildren = null;

    /**
     * Returns true if the document has at least one child
     */
    public function hasChildren(): bool
    {
        if ($this->hasChildren === null) {
            $this->hasChildren = $this->getDao()->hasChildren();
        }

        return $this->hasChildren;
    }

    public function getChildren(): array
    {
        if ($this->children === null) {
            if ($this->getId()) {
                $list = new Role\Listing();
                $list->setCondition('parentId = ?', $this->getId());

                $this->children = $list->getRoles();
            } else {
                $this->children = [];
            }
        }

        return $this->children;
    }

    /**
     * @return $this
     */
    public function setChildren(array $children): static
    {
        $this->children = $children;
        $this->hasChildren = count($children) > 0;

        return $this;
    }
}
