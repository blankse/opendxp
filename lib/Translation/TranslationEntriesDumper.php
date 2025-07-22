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

namespace OpenDxp\Translation;

use OpenDxp\Model\Translation;

class TranslationEntriesDumper
{
    /**
     * @var Translation[]
     */
    private static array $translations = [];

    public static function addToSaveQueue(Translation $translation): void
    {
        self::$translations[$translation->getKey()] = $translation;
    }

    public function dumpToDb(): void
    {
        foreach (self::$translations as $translation) {
            $translation->save();
        }
        self::$translations = [];
    }
}
