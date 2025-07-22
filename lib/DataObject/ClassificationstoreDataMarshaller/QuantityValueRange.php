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

namespace OpenDxp\DataObject\ClassificationstoreDataMarshaller;

use OpenDxp\Marshaller\MarshallerInterface;
use OpenDxp\Tool\Serialize;

/**
 * @internal
 */
class QuantityValueRange implements MarshallerInterface
{
    public function marshal(mixed $value, array $params = []): mixed
    {
        if (is_array($value)) {
            $minMaxValue = [
                'minimum' => $value['minimum'] ?? null,
                'maximum' => $value['maximum'] ?? null,
            ];

            return [
                'value' => Serialize::serialize($minMaxValue),
                'value2' => $value['unitId'] ?? null,
            ];
        }

        return null;
    }

    public function unmarshal(mixed $value, array $params = []): mixed
    {
        if (is_array($value) && ($value['value'] !== null || $value['value2'] !== null)) {
            $minMaxValue = Serialize::unserialize($value['value'] ?? null);

            return [
                'minimum' => $minMaxValue['minimum'] ?? null,
                'maximum' => $minMaxValue['maximum'] ?? null,
                'unitId' => $value['value2'],
            ];
        }

        return null;
    }
}
