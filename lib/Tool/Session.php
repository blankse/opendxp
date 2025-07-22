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

namespace OpenDxp\Tool;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class Session
{
    /**
     * @param callable(AttributeBagInterface, SessionInterface):mixed $func
     */
    public static function useBag(SessionInterface $session, callable $func, string $namespace = 'opendxp_admin'): mixed
    {
        $bag = $session->getBag($namespace);

        if ($bag instanceof AttributeBagInterface) {
            return $func($bag, $session);
        }

        throw new InvalidArgumentException(sprintf('The Bag "%s" is not a AttributeBagInterface.', $namespace));
    }

    public static function getSessionBag(
        SessionInterface $session,
        string $namespace = 'opendxp_admin'
    ): ?AttributeBagInterface {
        $bag = $session->getBag($namespace);
        if ($bag instanceof AttributeBagInterface) {
            return $bag;
        }

        return null;
    }
}
