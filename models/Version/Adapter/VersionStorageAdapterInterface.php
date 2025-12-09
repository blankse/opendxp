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

namespace OpenDxp\Model\Version\Adapter;

use OpenDxp\Model\Version;

interface VersionStorageAdapterInterface
{
    public function getStorageType(?int $metaDataSize = null,
        ?int $binaryDataSize = null): string;

    /**
     * @param resource|null $binaryDataStream
     */
    public function save(Version $version, string $metaData, mixed $binaryDataStream): void;

    public function loadMetaData(Version $version): ?string;

    public function loadBinaryData(Version $version): mixed;

    public function getBinaryFileStream(Version $version): mixed;

    public function getFileStream(Version $version): mixed;

    public function delete(Version $version, bool $isBinaryHashInUse): void;
}
