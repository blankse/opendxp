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

namespace OpenDxp\Model\Asset\MetaData\ClassDefinition\Data;

use Exception;

interface DataDefinitionInterface
{
    public function isEmpty(mixed $data, array $params = []): bool;

    /**
     * @throws Exception
     */
    public function checkValidity(mixed $data, array $params = []): void;

    public function getDataForListfolderGrid(mixed $data, array $params = []): mixed;

    public function getDataFromEditMode(mixed $data, array $params = []): mixed;

    public function getDataFromListfolderGrid(mixed $data, array $params = []): mixed;

    public function resolveDependencies(mixed $data, array $params = []): array;
}
