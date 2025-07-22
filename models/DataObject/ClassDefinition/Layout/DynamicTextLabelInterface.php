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

namespace OpenDxp\Model\DataObject\ClassDefinition\Layout;

use OpenDxp\Model\DataObject\Concrete;

interface DynamicTextLabelInterface
{
    /**
     * @param string $data as provided in the class definition
     * @param array<string, mixed> $params
     */
    public function renderLayoutText(string $data, ?Concrete $object, array $params): string;
}
