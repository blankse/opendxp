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

namespace OpenDxp\Security\Hasher;

use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\RuntimeException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @internal
 */
interface UserAwarePasswordHasherInterface extends PasswordHasherInterface
{
    /**
     * Set the user
     *
     *
     * @throws RuntimeException
     *      if the user is already set to prevent overwriting the scoped user object
     */
    public function setUser(UserInterface $user): void;

    /**
     * Get the user object
     *
     *
     * @throws RuntimeException
     *      if no user was set
     */
    public function getUser(): UserInterface;
}
