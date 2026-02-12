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

namespace OpenDxp\Tests\Model\DataType;

use Exception;
use OpenDxp\Model\Asset;
use OpenDxp\Model\DataObject\ClassDefinition\Data;
use OpenDxp\Model\DataObject\Data\Link;
use OpenDxp\Model\DataObject\Service;
use OpenDxp\Model\DataObject\unittestLink;
use OpenDxp\Model\Element\ValidationException;
use OpenDxp\Tests\Support\Test\ModelTestCase;
use OpenDxp\Tests\Support\Util\TestHelper;
use Throwable;
use TypeError;

/**
 * Class LinkTest
 *
 * @group model.datatype.link
 */
class LinkTest extends ModelTestCase
{
    protected Asset $testAsset;

    protected Link $link;

    protected Data $linkDefinition;

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
        $this->tester->setupOpenDxpClass_Link();
    }

    /**
     * Prepares objects for internal link tests
     *
     * @throws Exception
     */
    protected function setupInternalLinkObjects(): void
    {
        $this->testAsset = TestHelper::createImageAsset();

        $link = new Link();
        $link->setInternal($this->testAsset->getId());
        $link->setInternalType('asset');
        $this->link = $link;

        $linkObject = $this->createLinkObject();
        $linkObject->setTestlink($link);
        $this->linkDefinition = $linkObject->getClass()->getFieldDefinition('testlink');
    }

    /**
     * @throws Exception
     */
    protected function createLinkObject(): unittestLink
    {
        $object = new unittestLink();
        $object->setParent(Service::createFolderByPath('/links'));
        $object->setKey('link1');
        $object->setPublished(true);

        return $object;
    }

    /**
     * Verifies that Link data is loaded correctly after save and reload
     *
     * @throws Exception
     */
    public function testSave(): void
    {
        $linkObject = $this->createLinkObject();
        $link = new Link();
        $link->setDirect('https://www.opendxp.io/');
        $linkObject->setTestlink($link);
        $linkObject->setLtestlink($link);
        $linkObject->save();

        $linkObjectReloaded = unittestLink::getById($linkObject->getId(), ['force' => true]);

        $this->assertEquals($link->getDirect(), $linkObjectReloaded->getTestlink()->getDirect());
        $this->assertEquals($link->getDirect(), $linkObjectReloaded->getLtestlink()->getDirect());
    }

    /**
     * Verifies that checkValidity method throws correct exception if invalid data is provided
     */
    public function testInternalCheckValidity(): void
    {
        $this->setupInternalLinkObjects();
        $this->testAsset->delete();

        //Should return validation exception as asset was deleted
        $this->expectException(ValidationException::class);
        $this->linkDefinition->checkValidity($this->link);
    }

    /**
     * Verifies that checkValidity method sanitize the link data if invalid data is provided
     */
    public function testInternalCheckValidityParam(): void
    {
        $this->setupInternalLinkObjects();
        $this->testAsset->delete();
        //Should not return validation exception as parameter is set
        $this->linkDefinition->checkValidity($this->link, true, ['resetInvalidFields' => true]);

        //Should return sanitized link data
        $this->assertTrue($this->link->getInternal() === null);
        $this->assertTrue($this->link->getInternalType() === null);
    }

    /**
     * Verifies that Link data throws correct exceptions if invalid data is given
     *
     * @throws Exception
     */
    public function testCheckValidity(): void
    {
        try {
            $linkObject = $this->createLinkObject();
            $linkObject->setTestlink('https://www.opendxp.io/');
            $linkObject->setLtestlink('https://www.opendxp.io/');
            $this->fail('Expected a TypeError');
        } catch (Throwable $e) {
            $this->assertInstanceOf(TypeError::class, $e);
        }
    }
}
