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

namespace OpenDxp\Model\DataObject\ClassDefinition\Helper;

/**
 * @internal
 */
interface VarExportInterface
{
    /**
     * @return string[]
     */
    public function getBlockedVarsForExport(): array;

    /**
     * @param string[] $vars
     *
     * @return $this
     */
    public function setBlockedVarsForExport(array $vars): static;

    /**
     * @return string[]
     */
    public function resolveBlockedVars(): array;

    /**
     * @param array<string, mixed> $data
     */
    public static function __set_state(array $data): static;
}
