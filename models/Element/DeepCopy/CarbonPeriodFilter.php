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

namespace OpenDxp\Model\Element\DeepCopy;

use Carbon\CarbonPeriod;
use DeepCopy\TypeFilter\TypeFilter;

final class CarbonPeriodFilter implements TypeFilter
{
    public const string TYPE = CarbonPeriod::class;

    public function apply(mixed $element): CarbonPeriod
    {
        return CarbonPeriod::instance($element);
    }
}
