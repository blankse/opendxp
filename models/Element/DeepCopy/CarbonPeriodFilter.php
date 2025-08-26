<?php
declare(strict_types=1);

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
