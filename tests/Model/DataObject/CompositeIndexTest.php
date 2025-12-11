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

namespace OpenDxp\Tests\Model\DataObject;

use Exception;
use OpenDxp\Db;
use OpenDxp\Model\DataObject\ClassDefinition;
use OpenDxp\Model\DataObject\Unittest;
use OpenDxp\Tests\Support\Test\ModelTestCase;

class CompositeIndexTest extends ModelTestCase
{
    public function testAddIndex(): void
    {
        $classId = Unittest::classId();
        $db = Db::get();

        try {
            $db->executeQuery('ALTER TABLE `object_query_' . $classId . '` DROP INDEX `mycomposite`');
            $this->fail('expected that the index does not exist yet');
        } catch (Exception $e) {
        }

        $definition = ClassDefinition::getById($classId);
        $definition->setCompositeIndices([
           [
               'index_key' => 'mycomposite',
               'index_type' => 'query',
               'index_columns' => [
                   'slider', 'number',
               ],
           ],
        ]);

        $definition->save();

        // this will throw an exception if the index does not exist
        $db->executeQuery('ALTER TABLE `object_query_' . $classId . '` DROP INDEX `c_mycomposite`');
    }
}
