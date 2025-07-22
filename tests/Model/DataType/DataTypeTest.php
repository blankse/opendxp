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

namespace OpenDxp\Tests\Model\DataType;

use OpenDxp\Model\DataObject\AbstractObject;
use OpenDxp\Model\DataObject\Unittest;
use OpenDxp\Tests\Support\Test\DataType\AbstractDataTypeTestCase;
use OpenDxp\Tests\Support\Util\TestHelper;

/**
 * @group dataTypeLocal
 */
class DataTypeTest extends AbstractDataTypeTestCase
{
    /**
     * Creates and saves object locally without testing against a comparison object
     */
    protected function createTestObject(array|string $fields = [], ?array &$returnData = []): Unittest
    {
        $object = TestHelper::createEmptyObject('local', true, true);
        if ($fields) {
            if (isset(func_get_args()[1])) {
                $this->fillObject($object, $fields, $returnData);
            } else {
                $this->fillObject($object, $fields);
            }
        }

        $object->save();

        $this->assertNotNull($object);
        $this->assertInstanceOf(Unittest::class, $object);

        $this->testObject = $object;

        return $this->testObject;
    }

    public function refreshObject(): void
    {
        $this->testObject = AbstractObject::getById($this->testObject->getId(), ['force' => true]);
    }
}
