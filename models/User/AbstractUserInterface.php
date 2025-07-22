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

namespace OpenDxp\Model\User;

use Exception;
use OpenDxp\Model\ModelInterface;

/**
 * @method void setLastLoginDate()
 */
interface AbstractUserInterface extends ModelInterface
{
    public function getId(): ?int;

    /**
     * @return $this
     */
    public function setId(int $id): static;

    public function getParentId(): ?int;

    /**
     * @return $this
     */
    public function setParentId(int $parentId): static;

    public function getName(): ?string;

    /**
     * @return $this
     */
    public function setName(string $name): static;

    public function getType(): string;

    /**
     * @return $this
     *
     * @throws Exception
     */
    public function save(): static;

    /**
     * @throws Exception
     */
    public function delete(): void;

    /**
     * @return $this
     */
    public function setType(string $type): static;
}
