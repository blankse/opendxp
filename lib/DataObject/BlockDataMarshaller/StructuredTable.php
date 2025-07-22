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

namespace OpenDxp\DataObject\BlockDataMarshaller;

use OpenDxp\Marshaller\MarshallerInterface;

/**
 * @internal
 */
class StructuredTable implements MarshallerInterface
{
    public function marshal(mixed $value, array $params = []): mixed
    {
        if (is_array($value)) {
            $table = new \OpenDxp\Model\DataObject\Data\StructuredTable();
            $table->setData($value);

            return $table;
        }

        return null;
    }

    public function unmarshal(mixed $value, array $params = []): mixed
    {
        if ($value instanceof \OpenDxp\Model\DataObject\Data\StructuredTable) {
            return $value->getData();
        }

        return null;
    }
}
