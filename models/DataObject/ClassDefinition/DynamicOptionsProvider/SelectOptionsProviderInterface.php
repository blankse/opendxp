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

namespace OpenDxp\Model\DataObject\ClassDefinition\DynamicOptionsProvider;

use OpenDxp\Model\DataObject\ClassDefinition\Data;

interface SelectOptionsProviderInterface
{
    public function getOptions(array $context, Data $fieldDefinition): array;

    /**
     * Whether options are depending on the object context (i.e. different options for different objects) or not.
     * This is especially important for exposing options in the object grid. For options depending on object-context
     * there will be no batch assignment mode, and filtering can only be done through a text field instead of the
     * options list.
     */
    public function hasStaticOptions(array $context, Data $fieldDefinition): bool;

    /**
     * @return string|array<string|array{value: string}>|null
     */
    public function getDefaultValue(array $context, Data $fieldDefinition): string|array|null;
}
