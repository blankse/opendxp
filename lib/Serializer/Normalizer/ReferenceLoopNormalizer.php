<?php

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

namespace OpenDxp\Serializer\Normalizer;

use ArrayObject;
use JsonSerializable;
use OpenDxp\Tool\Serialize;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @internal
 */
class ReferenceLoopNormalizer implements NormalizerInterface
{
    public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|ArrayObject|null
    {
        $object = Serialize::removeReferenceLoops($object);

        if ($object instanceof JsonSerializable) {
            return $object->jsonSerialize();
        }

        if (is_object($object)) {
            $propCollection = get_object_vars($object);

            $array = [];
            foreach ($propCollection as $name => $propValue) {
                $array[$name] = $propValue;
            }

            return $array;
        }

        return $object;
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $format === JsonEncoder::FORMAT;
    }

    public function getSupportedTypes(?string $format): array
    {
        return ['*' => false];
    }
}
