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

use Carbon\Carbon;
use OpenDxp\Marshaller\MarshallerInterface;

/**
 * @internal
 */
class Date implements MarshallerInterface
{
    public function marshal(mixed $value, array $params = []): mixed
    {
        if ($value !== null) {
            $result = new Carbon();
            $result->setTimestamp($value);

            return $result;
        }

        return null;
    }

    public function unmarshal(mixed $value, array $params = []): mixed
    {
        if ($value instanceof Carbon) {
            return $value->getTimestamp();
        }

        return null;
    }
}
