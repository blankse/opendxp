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

namespace OpenDxp\Extension\Bundle\Installer;

use OpenDxp\Extension\Bundle\Installer\Exception\InstallationException;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\NullOutput;

interface InstallerInterface
{
    /**
     * Installs the bundle
     *
     * @throws InstallationException
     */
    public function install(): void;

    /**
     * Uninstalls the bundle
     *
     * @throws InstallationException
     */
    public function uninstall(): void;

    /**
     * Determine if bundle is installed
     */
    public function isInstalled(): bool;

    /**
     * Determine if bundle is ready to be installed. Can be used to check prerequisites
     */
    public function canBeInstalled(): bool;

    /**
     * Determine if bundle can be uninstalled
     */
    public function canBeUninstalled(): bool;

    /**
     * Determines if admin interface should be reloaded after installation/uninstallation
     */
    public function needsReloadAfterInstall(): bool;

    public function getOutput(): BufferedOutput | NullOutput;
}
