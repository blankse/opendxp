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

namespace OpenDxp\Model\Document\Editable;

interface EditableInterface
{
    /**
     * Renders the editable, calls either frontend() or admin() depending on the context
     */
    public function render(): mixed;

    /**
     * Get the current data stored for the element
     * this is used as general fallback for the methods getDataForResource(), admin(), getValue()
     */
    public function getData(): mixed;

    /**
     * Return the type of the element
     */
    public function getType(): string;

    /**
     * Receives the data from the editmode and convert this to the internal data in the object eg. image-id to Asset\Image
     *
     *
     * @return $this
     */
    public function setDataFromEditmode(mixed $data): static;

    /**
     * Receives the data from the resource, an convert to the internal data in the object eg. image-id to Asset\Image
     *
     *
     * @return $this
     */
    public function setDataFromResource(mixed $data): static;

    public function isEmpty(): bool;
}
