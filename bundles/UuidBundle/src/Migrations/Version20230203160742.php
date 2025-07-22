<?php

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

namespace OpenDxp\Bundle\UuidBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230203160742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Modify `itemId` column type in `uuids` db table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `uuids` MODIFY COLUMN `itemId` VARCHAR(50) NOT NULL;');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `uuids` MODIFY COLUMN `itemId` int(11) unsigned NOT NULL;');
    }
}
