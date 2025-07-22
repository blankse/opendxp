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

namespace OpenDxp\Extension\Bundle\Traits;

use Composer\InstalledVersions;
use OpenDxp\Composer\PackageInfo;

/**
 * Exposes a simple getVersion() and getComposerPackageName() implementation by looking up the installed versions
 * via composer's version info which is generated on composer install.
 */
trait PackageVersionTrait
{
    /**
     * Returns the composer package name used to resolve the version
     */
    public function getComposerPackageName(): string
    {
        foreach (InstalledVersions::getAllRawData() as $installed) {
            foreach ($installed['versions'] as $packageName => $packageInfo) {
                if (!isset($packageInfo['install_path'])) {
                    // It's a replaced or provided (virtual) package
                    continue;
                }

                if (str_starts_with(__DIR__, realpath($packageInfo['install_path']))) {
                    return $packageName;
                }
            }
        }

        return '';
    }

    public function getVersion(): string
    {
        $version = InstalledVersions::getPrettyVersion($this->getComposerPackageName());

        // normalizes e.g. 'v2.3.0' to '2.3.0'
        $version = preg_replace('/^v/', '', $version);

        return $version;
    }

    public function getDescription(): string
    {
        $packageInfo = new PackageInfo();

        foreach ($packageInfo->getInstalledPackages('opendxp-bundle') as $bundle) {
            if ($bundle['name'] === $this->getComposerPackageName()) {
                return $bundle['description'] ?? '';
            }
        }

        return '';
    }
}
