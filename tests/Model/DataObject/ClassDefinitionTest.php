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

use OpenDxp\Model\DataObject\ClassDefinition;
use OpenDxp\Tests\Support\Test\ModelTestCase;

/**
 * Class ObjectTest
 *
 * @package OpenDxp\Tests\Model\DataObject
 *
 * @group model.dataobject.object
 */
class ClassDefinitionTest extends ModelTestCase
{
    private function testSetterCode(string $fieldName, string $expectedSetterCode, bool $localizedField = false): void
    {
        $class = ClassDefinition::getByName('unittest');
        if ($localizedField) {
            $fd = $class->getFieldDefinition('localizedfields')->getFieldDefinition($fieldName);
        } else {
            $fd = $class->getFieldDefinition($fieldName);
        }
        $setterCode = $fd->getSetterCode($class);
        $this->assertEquals($expectedSetterCode, $setterCode);
    }

    /**
     * Verifies that the class definition gets renamed properly
     */
    public function testRename(): void
    {
        $class = ClassDefinition::getByName('unittest');
        $class->rename('unittest_renamed');

        $renamedClass = ClassDefinition::getByName('unittest_renamed');
        $renamedClass->rename('unittest');
    }

    /**
     * Verifies that the setter code gets created properly
     */
    public function testInputSetterCode(): void
    {
        $expectedSetterCode =
            '/**
* Set input - input
* @param string|null $input
* @return $this
*/
public function setInput(?string $input): static
{
	$this->markFieldDirty("input", true);

	$this->input = $input;

	return $this;
}

';
        $this->testSetterCode('input', $expectedSetterCode);
    }

    /**
     * Verifies that the setter code gets created properly
     */
    public function testFieldCollectionSetterCode(): void
    {
        $expectedSetterCode =
            '/**
* Set fieldcollection - fieldcollection
* @param \OpenDxp\Model\DataObject\Fieldcollection|null $fieldcollection
* @return $this
*/
public function setFieldcollection(?\OpenDxp\Model\DataObject\Fieldcollection $fieldcollection): static
{
	/** @var \OpenDxp\Model\DataObject\ClassDefinition\Data\Fieldcollections $fd */
	$fd = $this->getClass()->getFieldDefinition("fieldcollection");
	$this->fieldcollection = $fd->preSetData($this, $fieldcollection);
	return $this;
}

';
        $this->testSetterCode('fieldcollection', $expectedSetterCode);
    }

    /**
     * Verifies that the setter code gets created properly
     */
    public function testBricksSetterCode(): void
    {
        $expectedSetterCode =
            '/**
* Set mybricks - mybricks
* @param \OpenDxp\Model\DataObject\Objectbrick|null $mybricks
* @return $this
*/
public function setMybricks(?\OpenDxp\Model\DataObject\Objectbrick $mybricks): static
{
	/** @var \OpenDxp\Model\DataObject\ClassDefinition\Data\Objectbricks $fd */
	$fd = $this->getClass()->getFieldDefinition("mybricks");
	$this->mybricks = $fd->preSetData($this, $mybricks);
	return $this;
}

';
        $this->testSetterCode('mybricks', $expectedSetterCode);
    }

    /**
     * Verifies that the setter code gets created properly
     */
    public function testQuantityValueSetterCode(): void
    {
        $expectedSetterCode =
            '/**
* Set quantityValue - quantityValue
* @param \OpenDxp\Model\DataObject\Data\QuantityValue|null $quantityValue
* @return $this
*/
public function setQuantityValue(?\OpenDxp\Model\DataObject\Data\QuantityValue $quantityValue): static
{
	$this->markFieldDirty("quantityValue", true);

	$this->quantityValue = $quantityValue;

	return $this;
}

';
        $this->testSetterCode('quantityValue', $expectedSetterCode);
    }

    /**
     * Verifies that the setter code gets created properly
     */
    public function testLocalizedFieldSetterCode(): void
    {
        $expectedSetterCode =
            '/**
* Set linput - linput
* @param string|null $linput
* @return $this
*/
public function setLinput(?string $linput): static
{
	$this->markFieldDirty("linput", true);

	$this->linput = $linput;

	return $this;
}

';
        $this->testSetterCode('linput', $expectedSetterCode, true);
    }
}
