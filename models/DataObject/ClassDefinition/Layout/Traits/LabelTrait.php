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

namespace OpenDxp\Model\DataObject\ClassDefinition\Layout\Traits;

/**
 * @internal
 */
trait LabelTrait
{
    /**
     * Width of input field labels
     *
     * @internal
     */
    public int $labelWidth = 100;

    /**
     * @internal
     */
    public string $labelAlign = 'left';

    /**
     * @return $this
     */
    public function setLabelWidth(int $labelWidth): static
    {
        $this->labelWidth = $labelWidth;

        return $this;
    }

    public function getLabelWidth(): int
    {
        return $this->labelWidth;
    }

    /**
     * @return $this
     */
    public function setLabelAlign(string $labelAlign): static
    {
        if ($labelAlign) {
            $this->labelAlign = $labelAlign;
        }

        return $this;
    }

    public function getLabelAlign(): string
    {
        return $this->labelAlign;
    }
}
