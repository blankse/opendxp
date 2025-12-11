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

use OpenDxp\Model\DataObject\AbstractObject;
use OpenDxp\Model\DataObject\Inheritance;
use OpenDxp\Model\Element\ElementInterface;
use OpenDxp\Tests\Support\Test\AbstractPropertiesTest;
use OpenDxp\Tests\Support\Util\TestHelper;

/**
 * @group properties
 */
class PropertiesTest extends AbstractPropertiesTest
{
    public function createElement(): ElementInterface
    {
        $this->testElement = TestHelper::createEmptyObject('local', true, true, '\\OpenDxp\\Model\\DataObject\\Inheritance');
        $this->testElement->save();

        $this->assertNotNull($this->testElement);
        $this->assertInstanceOf(Inheritance::class, $this->testElement);

        return $this->testElement;
    }

    public function reloadElement(): ElementInterface
    {
        $this->testElement = AbstractObject::getById($this->testElement->getId(), ['force' => true]);

        return $this->testElement;
    }
}
