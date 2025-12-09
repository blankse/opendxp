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

namespace OpenDxp\Tool;

/**
 * @internal
 */
class ArrayNormalizer
{
    /**
     * @var callable[]
     */
    private array $normalizers = [];

    public function normalize(array $array): array
    {
        foreach ($this->normalizers as $property => $normalizer) {
            if (!isset($array[$property])) {
                continue;
            }

            $array[$property] = $normalizer($array[$property], $property, $array);
        }

        return $array;
    }

    /**
     * @param int|string|int[]|string[] $properties
     */
    public function addNormalizer(array|int|string $properties, callable $normalizer): void
    {
        if (!is_array($properties)) {
            $properties = [$properties];
        }

        foreach ($properties as $property) {
            $this->normalizers[$property] = $normalizer;
        }
    }
}
