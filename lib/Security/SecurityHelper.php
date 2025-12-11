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

namespace OpenDxp\Security;

/**
 * @internal
 */
class SecurityHelper
{
    public static function convertHtmlSpecialChars(?string $text): ?string
    {
        if (is_string($text)) {
            return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false);
        }

        return null;
    }

    public static function convertHtmlSpecialCharsArrayKeys(array &$array, array $keys): void
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $array)) {
                $array[$key] = self::convertHtmlSpecialChars($array[$key]);
            }
        }
    }
}
