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

namespace OpenDxp\Session\Attribute;

use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface;

interface LockableAttributeBagInterface extends AttributeBagInterface
{
    /**
     * Lock the attribute bag (disallow modifications)
     */
    public function lock(): void;

    /**
     * Unlock the attribute bag
     */
    public function unlock(): void;

    /**
     * Get lock status
     */
    public function isLocked(): bool;
}
