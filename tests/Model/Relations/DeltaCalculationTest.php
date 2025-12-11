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

namespace OpenDxp\Tests\Model\Relations;

use OpenDxp\Model\DataObject\Data\ElementMetadata;
use OpenDxp\Model\DataObject\MultipleAssignments;
use OpenDxp\Model\DataObject\RelationTest;
use OpenDxp\Model\DataObject\Service;
use OpenDxp\Tests\Support\Test\ModelTestCase;
use OpenDxp\Tests\Support\Util\TestHelper;

/**
 * Class DeltaCalculationTest
 *
 * @package OpenDxp\Tests\Model\Relations
 *
 * @group model.relations.multipleassignment
 */
class DeltaCalculationTest extends ModelTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        TestHelper::cleanUp();

        $this->createRelationObjects();
    }

    public function tearDown(): void
    {
        TestHelper::cleanUp();
        parent::tearDown();
    }

    protected function createRelationObjects(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $object = new RelationTest();
            $object->setParent(Service::createFolderByPath('__test/relationobjects'));
            $object->setKey("relation-$i");
            $object->setPublished(true);
            $object->setSomeAttribute("Some content $i");
            $object->save();
        }
    }

    protected function setUpTestClasses(): void
    {
        $this->tester->setupOpenDxpClass_RelationTest();
        $this->tester->setupOpenDxpClass_MultipleAssignments();
    }

    public function testDeltaManyToMany(): void
    {
        $listing = new RelationTest\Listing();
        $listing->setLimit(5);

        $object = new MultipleAssignments();
        $object->setParent(Service::createFolderByPath('/assignments'));
        $object->setKey('test1');
        $object->setPublished(true);

        $metaDataList = [];

        foreach ($listing as $i => $item) {
            $objectMetadata = new ElementMetadata('multipleManyToMany', ['meta'], $item);
            $objectMetadata->setMeta("multiple-some-metadata $i");
            $metaDataList[] = $objectMetadata;
        }

        $fds = $object->getClass()->getFieldDefinitions();
        $fd = $fds['multipleManyToMany'];

        // Insert 5 relations
        $object->setMultipleManyToMany($metaDataList);
        $this->deltaCheck([5, 0, 0, 0], $fd, $object);
        $object->save();

        // Remove last relation
        array_pop($metaDataList);
        $object->setMultipleManyToMany($metaDataList);
        $this->deltaCheck([0, 4, 0, 1], $fd, $object);
        $object->save();

        // Remove all
        $object->setMultipleManyToMany([]);
        $this->deltaCheck([0, 0, 0, 4], $fd, $object);
        $object->save();

        // Re-insert 4, also re-check existing are 0
        $object->setMultipleManyToMany($metaDataList);
        $this->deltaCheck([4, 0, 0, 0], $fd, $object);
        $object->save();

        // Swap 1 and 2
        $metaDataList = $this->swapOrder($metaDataList, 1, 2);
        $object->setMultipleManyToMany($metaDataList);
        $this->deltaCheck([0, 2, 2, 0], $fd, $object);
        $object->save();
        $multipleManyToMany = $object->getMultipleManyToMany();
        $this->metaOrderCheck([0, 2, 1, 3], $multipleManyToMany);

        // Swap 0 and 2 and delete first one at the same time
        $newMetaDataList = $object->getMultipleManyToMany();
        $newMetaDataList = $this->swapOrder($newMetaDataList, 0, 2);
        array_shift($newMetaDataList);
        $object->setMultipleManyToMany($newMetaDataList);
        $this->deltaCheck([0, 0, 3, 1], $fd, $object);
        $object->save();
        $multipleManyToMany = $object->getMultipleManyToMany();
        $this->metaOrderCheck([2, 0, 3], $multipleManyToMany);
    }

    /**
     * @param array $expectedValues Pass in the CRUD order, C for new, R for existing, U for updated, D for removed
     */
    protected function deltaCheck(array $expectedValues, $fd, $object): void
    {
        $delta = $fd->calculateDelta($object, [
            'context'=> [
                'containerType'=>'object',
            ],
        ]);

        $this->assertCount($expectedValues[0], $delta['newRelations'], 'New relations count');
        $this->assertCount($expectedValues[1], $delta['existingRelations'], 'Existing relations count');
        $this->assertCount($expectedValues[2], $delta['updatedRelations'], 'Updated relations count');
        $this->assertCount($expectedValues[3], $delta['removedRelations'], 'Removed relations count');
    }

    protected function metaOrderCheck(array $expectedValues, array $data): void
    {
        foreach ($data as $i => $relation) {
            $this->assertEquals('multiple-some-metadata '. $expectedValues[$i], $relation->getMeta(), 'Metadata order check');
        }

    }

    private function swapOrder(array $data, int $from, int $to): array
    {
        $temp = $data[$from];
        $data[$from] = $data[$to];
        $data[$to] = $temp;

        return $data;
    }
}
