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

namespace OpenDxp\Event\Model;

use InvalidArgumentException;
use OpenDxp\Model\Element\ElementInterface;

interface ElementEventInterface
{
    public function getElement(): ElementInterface;

    /**
     * Get argument by key.
     *
     * @param string $key Key
     *
     * @return mixed Contents of array key
     *
     * @throws InvalidArgumentException If key is not found.
     */
    public function getArgument(string $key): mixed;

    /**
     * Add argument to event.
     *
     * @param string $key   Argument name
     * @param mixed  $value Value
     *
     * @return $this
     */
    public function setArgument(string $key, mixed $value): static;

    /**
     * Getter for all arguments.
     */
    public function getArguments(): array;

    /**
     * Set args property.
     *
     * @param array $args Arguments
     *
     * @return $this
     */
    public function setArguments(array $args = []): static;

    /**
     * Has argument.
     *
     * @param string $key Key of arguments array
     */
    public function hasArgument(string $key): bool;
}
