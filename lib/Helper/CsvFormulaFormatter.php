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

namespace OpenDxp\Helper;

use League\Csv\EscapeFormula;

/**
 * @deprecated and will be removed in OpenDxp 12. Use \League\Csv\EscapeFormula instead.
 */
class CsvFormulaFormatter extends EscapeFormula
{
    public function unEscapeField(mixed $field): string
    {
        trigger_deprecation(
            'open-dxp/opendxp',
            '11.1.0',
            sprintf('The "%s" class is deprecated, use "%s" instead.', __CLASS__, EscapeFormula::class)
        );

        if (isset($field[0], $field[1])
            && $field[0] === $this->getEscape()
            && in_array($field[1], $this->getSpecialCharacters())
        ) {
            return ltrim($field, $field[0]);
        }

        return $field;
    }
}
