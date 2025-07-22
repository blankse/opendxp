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

use Symfony\Component\PasswordHasher\Hasher\PlaintextPasswordHasher;
use Symfony\Component\Security\Core\Exception\RuntimeException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @internal
 */
abstract class AbstractUserAwarePasswordHasher extends PlaintextPasswordHasher implements UserAwarePasswordHasherInterface
{
    protected ?UserInterface $user = null;

    public function setUser(UserInterface $user): void
    {
        if ($this->user) {
            throw new RuntimeException('User was already set and can\'t be overwritten');
        }

        $this->user = $user;
    }

    public function getUser(): UserInterface
    {
        if (!$this->user) {
            throw new RuntimeException('No user was set');
        }

        return $this->user;
    }
}
