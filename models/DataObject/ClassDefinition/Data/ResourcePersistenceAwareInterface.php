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

namespace OpenDxp\Model\DataObject\ClassDefinition\Data;

use OpenDxp\Model\DataObject\Concrete;

interface ResourcePersistenceAwareInterface
{
    /**
     * Returns the the data that should be stored in the resource
     */
    public function getDataForResource(mixed $data, ?Concrete $object = null, array $params = []): mixed;

    /**
     * Convert the saved data in the resource to the internal eg. Image-Id to Asset\Image object, this is the inverted getDataForResource()
     */
    public function getDataFromResource(mixed $data, ?Concrete $object = null, array $params = []): mixed;

    public function getColumnType(): array|string;
}
