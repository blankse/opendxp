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

use OpenDxp;
use Symfony\Component\HttpKernel\Bundle\Bundle;

abstract class AbstractOpenDxpBundle extends Bundle implements OpenDxpBundleInterface
{
    public function getNiceName(): string
    {
        return $this->getName();
    }

    public function getDescription(): string
    {
        return '';
    }

    public function getVersion(): string
    {
        return '';
    }

    public function getInstaller(): ?Installer\InstallerInterface
    {
        return null;
    }

    public static function isInstalled(): bool
    {
        $bundleManager = OpenDxp::getContainer()->get(OpenDxpBundleManager::class);
        $bundle = $bundleManager->getActiveBundle(static::class, false);

        return $bundleManager->isInstalled($bundle);
    }
}
