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

namespace OpenDxp\Model\DataObject\Data;

use OpenDxp\Model\DataObject\OwnerAwareFieldInterface;
use OpenDxp\Model\DataObject\Traits\OwnerAwareFieldTrait;

class NumericRange implements OwnerAwareFieldInterface
{
    use OwnerAwareFieldTrait;

    protected int|null|float $minimum = null;

    protected int|null|float $maximum = null;

    public function __construct(float|int|null $minimum, float|int|null $maximum)
    {
        $this->minimum = $minimum;
        $this->maximum = $maximum;

        $this->markMeDirty();
    }

    public function getMinimum(): float|int|null
    {
        return $this->minimum;
    }

    public function setMinimum(float|int|null $minimum): void
    {
        $this->minimum = $minimum;

        $this->markMeDirty();
    }

    public function getMaximum(): float|int|null
    {
        return $this->maximum;
    }

    public function setMaximum(float|int|null $maximum): void
    {
        $this->maximum = $maximum;

        $this->markMeDirty();
    }

    public function getRange(int|float $step = 1): array
    {
        return range($this->getMinimum(), $this->getMaximum(), $step);
    }

    public function toArray(): array
    {
        return [
            'minimum' => $this->getMinimum(),
            'maximum' => $this->getMaximum(),
        ];
    }

    public function __toString(): string
    {
        $minimum = $this->getMinimum() ?: '-∞';
        $maximum = $this->getMaximum() ?: '+∞';

        return sprintf('[%s, %s]', $minimum, $maximum);
    }
}
