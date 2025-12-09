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

namespace OpenDxp\Tests\Model\Element;

use OpenDxp\Db;
use OpenDxp\Model\Asset;
use OpenDxp\Model\DataObject;
use OpenDxp\Model\DataObject\Concrete;
use OpenDxp\Model\DataObject\Unittest;
use OpenDxp\Model\Document;
use OpenDxp\Model\Element\ElementInterface;
use OpenDxp\Model\Property;
use OpenDxp\Tests\Support\Test\ModelTestCase;
use OpenDxp\Tests\Support\Util\TestHelper;

/**
 * Class DependenciesTest
 *
 * @package OpenDxp\Tests\Model\Element
 *
 * @group model.element.dependencies
 */
class DependenciesTest extends ModelTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        TestHelper::cleanUp();
    }

    public function testRelation(): void
    {
        /** @var Unittest $source */
        $db = Db::get();
        $initialCount = $db->fetchOne('SELECT count(*) from dependencies');

        $source = TestHelper::createEmptyObject();
        $sourceId = $source->getId();

        $count = $db->fetchOne("SELECT count(*) from dependencies WHERE sourceType = 'object' AND sourceID = " . $sourceId);
        $this->assertEquals(0, $count);

        $targets = TestHelper::createEmptyObjects('', true, 5);
        $source->setMultihref([$targets[0], $targets[1]]);
        $source->save();

        $count = $db->fetchOne("SELECT count(*) from dependencies WHERE sourceType = 'object' AND sourceID = " . $sourceId);
        $this->assertEquals(2, $count);

        $count = $db->fetchOne("SELECT count(*) from dependencies WHERE sourceType = 'object' "
            . ' AND sourceID = ' . $sourceId . " AND targetType = 'object' AND targetId = " . $targets[1]->getId());
        $this->assertEquals(1, $count);

        $source->setMultihref([$targets[0], $targets[3], $targets[4]]);
        $source->save();

        $count = $db->fetchOne("SELECT count(*) from dependencies WHERE sourceType = 'object' "
            . ' AND sourceID = ' . $sourceId . " AND targetType = 'object' AND targetId = " . $targets[1]->getId());
        $this->assertEquals(0, $count);

        $count = $db->fetchOne("SELECT count(*) from dependencies WHERE sourceType = 'object' AND sourceID = " . $sourceId);
        $this->assertEquals(3, $count);

        $finalCount = $db->fetchOne('SELECT count(*) from dependencies');
        $this->assertEquals($initialCount + 3, $finalCount);

        $source->delete();
        $count = $db->fetchOne("SELECT count(*) from dependencies WHERE sourceType = 'object' AND sourceID = " . $sourceId);
        $this->assertEquals(0, $count);
    }

    /**
     * Verifies that an object requires and requiredBy dependencies are stored and fetched
     */
    public function testObjectDependencies(): void
    {
        $source = TestHelper::createEmptyObject();

        /** @var Unittest[] $targets */
        for ($i = 0; $i <= 2; $i++) {
            $targets[] = TestHelper::createEmptyObject((string)$i);
        }
        $this->saveElementDependencies($source, $targets);

        //Reload source object
        $source = DataObject::getById($source->getId(), ['force' => true]);

        //get dependencies
        $dependencies = $source->getDependencies();
        $requires = $dependencies->getRequires();
        $requiredBy = $dependencies->getRequiredBy();

        $this->assertCount(3, $requires, 'DataObject: requires dependencies not saved or loaded properly');
        $this->assertEquals($targets[0]->getId(), $requires[0]['id'], 'DataObject: requires dependency not saved or loaded properly');
        $this->assertEquals($targets[2]->getId(), $requiredBy[0]['id'], 'DataObject: requiredBy dependency not saved or loaded properly');
    }

    /**
     * Verifies that a document requires and requiredBy dependencies are stored and fetched
     */
    public function testDocumentDependencies(): void
    {
        $source = TestHelper::createEmptyDocumentPage();
        /** @var Unittest[] $targets */
        for ($i = 0; $i <= 2; $i++) {
            $targets[] = TestHelper::createEmptyObject((string)$i);
        }
        $this->saveElementDependencies($source, $targets);

        //Reload source document
        $source = Document::getById($source->getId(), ['force' => true]);

        //get dependencies
        $dependencies = $source->getDependencies();
        $requires = $dependencies->getRequires();
        $requiredBy = $dependencies->getRequiredBy();

        $this->assertCount(3, $requires, 'Document: requires dependencies not saved or loaded properly');
        $this->assertEquals($targets[0]->getId(), $requires[0]['id'], 'Document: requires dependency not saved or loaded properly');
        $this->assertEquals($targets[2]->getId(), $requiredBy[0]['id'], 'Document: requiredBy dependency not saved or loaded properly');
    }

    /**
     * Verifies that an asset requires and requiredBy dependencies are stored and fetched
     */
    public function testAssetDependencies(): void
    {
        $source = TestHelper::createImageAsset();
        /** @var Unittest[] $targets */
        $targets = [];
        for ($i = 0; $i <= 2; $i++) {
            $targets[] = TestHelper::createEmptyObject((string)$i);
        }

        $this->saveElementDependencies($source, $targets);

        //Reload source asset
        $source = Asset::getById($source->getId(), ['force' => true]);

        //get dependencies
        $dependencies = $source->getDependencies();
        $requires = $dependencies->getRequires();
        $requiredBy = $dependencies->getRequiredBy();

        $this->assertCount(3, $requires, 'Asset: requires dependencies not saved or loaded properly');
        $this->assertEquals($targets[0]->getId(), $requires[0]['id'], 'Asset: requires dependency not saved or loaded properly');
        $this->assertEquals($targets[2]->getId(), $requiredBy[0]['id'], 'Asset: requiredBy dependency not saved or loaded properly');
    }

    /**
     * @param Concrete[] $targets
     */
    private function saveElementDependencies(ElementInterface $source, array $targets): void
    {
        $properties = [];
        foreach ($targets as $idx => $target) {
            $propertyName = 'prop_' . $idx;
            $property = new Property();
            $property->setType('object');
            $property->setName($propertyName);
            $property->setCtype('object');
            $property->setDataFromEditmode($target);
            $properties[$propertyName] = $property;
        }

        $source->setProperties($properties);
        $source->save();

        $targets[2]->setMultihref([$source]);
        $targets[2]->save();
    }
}
