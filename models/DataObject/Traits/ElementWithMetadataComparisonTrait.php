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

namespace OpenDxp\Model\DataObject\Traits;

use OpenDxp\Model\DataObject\Data\ElementMetadata;
use OpenDxp\Model\Element\ElementInterface;

/**
 * @internal
 */
trait ElementWithMetadataComparisonTrait
{
    public function isEqual(mixed $oldValue, mixed $newValue): bool
    {
        $count1 = is_array($oldValue) ? count($oldValue) : 0;
        $count2 = is_array($newValue) ? count($newValue) : 0;

        if ($count1 !== $count2) {
            return false;
        }

        $values1 = array_filter(array_values(is_array($oldValue) ? $oldValue : []));
        $values2 = array_filter(array_values(is_array($newValue) ? $newValue : []));

        for ($i = 0; $i < $count1; $i++) {
            /** @var ElementMetadata|null $container1 */
            $container1 = $values1[$i];
            /** @var ElementMetadata|null $container2 */
            $container2 = $values2[$i];

            if (!$container1 || !$container2) {
                return !$container1 && !$container2;
            }

            /** @var ElementInterface|null $el1 */
            $el1 = $container1->getElement();
            /** @var ElementInterface|null $el2 */
            $el2 = $container2->getElement();

            if (! ($el1?->getType() == $el2?->getType() && ($el1?->getId() == $el2?->getId()))) {
                return false;
            }

            $data1 = $container1->getData();
            $data2 = $container2->getData();
            if ($data1 != $data2) {
                return false;
            }
        }

        return true;
    }
}
