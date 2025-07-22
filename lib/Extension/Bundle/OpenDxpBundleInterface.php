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

namespace OpenDxp\Extension\Bundle;

use OpenDxp\Extension\Bundle\Installer\InstallerInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

interface OpenDxpBundleInterface extends BundleInterface
{
    /**
     * Bundle name as shown in extension manager
     *
     */
    public function getNiceName(): string;

    /**
     * Bundle description as shown in extension manager
     *
     */
    public function getDescription(): string;

    /**
     * Bundle version as shown in extension manager
     *
     */
    public function getVersion(): string;

    /**
     * If the bundle has an installation routine, an installer is responsible of handling installation related tasks
     *
     */
    public function getInstaller(): ?InstallerInterface;
}
