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

namespace OpenDxp\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use OpenDxp;
use OpenDxp\Extension\Bundle\OpenDxpBundleInterface;

abstract class BundleAwareMigration extends AbstractMigration
{
    abstract protected function getBundleName(): string;

    protected function checkBundleInstalled(): bool
    {
        $bundle = OpenDxp::getKernel()->getBundle($this->getBundleName());
        if ($bundle instanceof OpenDxpBundleInterface) {
            $installer = $bundle->getInstaller();
            $this->skipIf($installer && !$installer->isInstalled(), 'Bundle not installed.');
        }

        return true;
    }

    public function preUp(Schema $schema): void
    {
        $this->checkBundleInstalled();
        parent::preUp($schema);
    }

    public function preDown(Schema $schema): void
    {
        $this->checkBundleInstalled();
        parent::preDown($schema);
    }
}
