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

namespace OpenDxp\DataObject\BlockDataMarshaller;

use OpenDxp\Marshaller\MarshallerInterface;

/**
 * @internal
 */
class Geobounds implements MarshallerInterface
{
    public function marshal(mixed $value, array $params = []): mixed
    {
        if (is_array($value)) {
            return [
                'value' => json_encode(['latitude' => $value['northEast']['latitude'], 'longitude' => $value['northEast']['longitude']]),
                'value2' => json_encode(['latitude' => $value['southWest']['latitude'], 'longitude' => $value['southWest']['longitude']]),
            ];
        }

        return null;
    }

    public function unmarshal(mixed $value, array $params = []): mixed
    {
        if (is_array($value)) {
            $northEast = json_decode($value['value'], true);
            $southWest = json_decode($value['value2'], true);

            return [
                'northEast' => $northEast,
                'southWest' => $southWest,
            ];
        }

        return null;
    }
}
