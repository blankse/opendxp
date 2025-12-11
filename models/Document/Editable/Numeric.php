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

namespace OpenDxp\Model\Document\Editable;

use OpenDxp\Model;

/**
 * @method \OpenDxp\Model\Document\Editable\Dao getDao()
 */
class Numeric extends Model\Document\Editable
{
    /**
     * Contains the current number, or an empty string if not set
     *
     * @internal
     */
    protected ?string $number = null;

    public function getType(): string
    {
        return 'numeric';
    }

    public function getData(): mixed
    {
        return $this->number;
    }

    /**
     * @see EditableInterface::getData
     */
    public function getNumber(): string
    {
        return $this->getData();
    }

    public function frontend()
    {
        return $this->number;
    }

    public function setDataFromResource(mixed $data): static
    {
        $this->number = $data;

        return $this;
    }

    public function setDataFromEditmode(mixed $data): static
    {
        $this->number = (string)$data;

        return $this;
    }

    public function isEmpty(): bool
    {
        if (is_numeric($this->number)) {
            return false;
        }

        return empty($this->number);
    }
}
