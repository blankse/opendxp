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

namespace OpenDxp\Tool;

/**
 * @internal
 */
class Transliteration
{
    public static function toASCII(string $value, ?string $language = null): string
    {
        if ($language !== null && in_array($language.'-ASCII', transliterator_list_ids())) {
            return transliterator_transliterate($language.'-ASCII; [^\u001F-\u007f] remove', $value);
        }

        return transliterator_transliterate('Any-Latin; Latin-ASCII; [^\u001F-\u007f] remove', $value);
    }
}
