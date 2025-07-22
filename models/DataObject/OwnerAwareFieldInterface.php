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

namespace OpenDxp\Model\DataObject;

interface OwnerAwareFieldInterface
{
    /**
     * @return $this
     */
    public function _setOwner(mixed $owner): static;

    public function _setOwnerFieldname(?string $fieldname): static;

    public function _setOwnerLanguage(?string $language): static;

    public function _getOwner(): mixed;

    public function _getOwnerFieldname(): ?string;

    public function _getOwnerLanguage(): ?string;
}
