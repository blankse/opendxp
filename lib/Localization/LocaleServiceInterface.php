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

namespace OpenDxp\Localization;

interface LocaleServiceInterface
{
    public function isLocale(string $locale): bool;

    public function findLocale(): string;

    public function getLocaleList(): array;

    public function getDisplayRegions(?string $locale = null): array;

    public function getLocale(): ?string;

    public function setLocale(?string $locale): void;

    public function hasLocale(): bool;
}
