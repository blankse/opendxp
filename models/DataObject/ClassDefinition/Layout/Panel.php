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

namespace OpenDxp\Model\DataObject\ClassDefinition\Layout;

use OpenDxp\Model;
use OpenDxp\Model\DataObject\ClassDefinition\Layout\Traits\IconTrait;
use OpenDxp\Model\DataObject\ClassDefinition\Layout\Traits\LabelTrait;

class Panel extends Model\DataObject\ClassDefinition\Layout
{
    use IconTrait;
    use LabelTrait;

    /**
     * Static type of this element
     *
     * @internal
     */
    public string $fieldtype = 'panel';

    /**
     * @internal
     */
    public ?string $layout = null;

    /**
     * @internal
     */
    public bool $border = false;

    /**
     * @return $this
     */
    public function setLayout(string $layout): static
    {
        $this->layout = $layout;

        return $this;
    }

    public function getLayout(): ?string
    {
        return $this->layout;
    }

    public function getBorder(): bool
    {
        return $this->border;
    }

    public function setBorder(bool $border): void
    {
        $this->border = $border;
    }
}
