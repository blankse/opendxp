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

use OpenDxp\Model\DataObject;
use OpenDxp\Model\DataObject\Fieldcollection;
use OpenDxp\Model\DataObject\RelationTest;
use OpenDxp\Model\DataObject\Service;
use OpenDxp\Tests\Support\Test\ModelTestCase;
use OpenDxp\Tests\Support\Util\TestHelper;

/**
 * Class ObjectbrickTest
 *
 * @package OpenDxp\Tests\Model\Relations
 *
 * @group model.relations.objectbrick
 */
class ObjectbrickTest extends ModelTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        TestHelper::cleanUp();
    }

    public function tearDown(): void
    {
        TestHelper::cleanUp();
        parent::tearDown();
    }

    protected function setUpTestClasses(): void
    {
        $this->tester->setupOpenDxpClass_RelationTest();
        $this->tester->setupObjectbrick_LazyLoadingTest();
    }

    public function testRelationFieldInsideObjectbrick(): void
    {
        $target1 = new RelationTest();
        $target1->setParent(Service::createFolderByPath('__test/relationobjects'));
        $target1->setKey('mytarget1');
        $target1->setPublished(true);
        $target1->save();

        $target2 = new RelationTest();
        $target2->setParent(Service::createFolderByPath('__test/relationobjects'));
        $target2->setKey('mytarget2');
        $target2->setPublished(true);
        $target2->save();

        $target3 = new RelationTest();
        $target3->setParent(Service::createFolderByPath('__test/relationobjects'));
        $target3->setKey('mytarget3');
        $target3->setPublished(true);
        $target3->save();

        /**
         * @var $object DataObject\Unittest
         */
        $object = TestHelper::createEmptyObject();

        $brick = new DataObject\Objectbrick\Data\UnittestBrick($object);
        $brick->setBrickLazyRelation([$target1, $target2]);
        $object->getMybricks()->setUnittestBrick($brick);
        $object->save();

        $rel = $brick->getBrickLazyRelation();
        $this->assertCount(2, $rel);

        /**
         * @var $object DataObject\Unittest
         */
        $object = DataObject::getById($object->getId(), ['force' => true]);

        /** @var Fieldcollection $fc */
        $brick = $object->getMybricks()->getUnittestBrick();
        $brick->setBrickLazyRelation([$target2]);
        $rel = $brick->getBrickLazyRelation();
        $this->assertCount(1, $rel);
        $object->save();

        /**
         * @var $object DataObject\Unittest
         */
        $object = DataObject::getById($object->getId(), ['force' => true]);
        $brick = $object->getMybricks()->getUnittestBrick();
        $rel = $brick->getBrickLazyRelation();
        $this->assertCount(1, $rel);
        $this->assertEquals($target2->getId(), $rel[0]->getId());

        //Flush relations
        $brick->setBrickLazyRelation(null);
        $object->save();

        /**
         * @var $object DataObject\Unittest
         */
        $object = DataObject::getById($object->getId(), ['force' => true]);
        $brick = $object->getMybricks()->getUnittestBrick();
        $rel = $brick->getBrickLazyRelation();
        $this->assertEquals([], $rel);
    }
}
