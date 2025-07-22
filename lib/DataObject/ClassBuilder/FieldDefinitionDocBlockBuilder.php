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

namespace OpenDxp\DataObject\ClassBuilder;

use OpenDxp\Model\DataObject\ClassDefinition;

class FieldDefinitionDocBlockBuilder implements FieldDefinitionDocBlockBuilderInterface
{
    public function buildFieldDefinitionDocBlock(ClassDefinition\Data $fieldDefinition, int $level = 1): string
    {
        $text = str_pad('', $level, '-').' '.$fieldDefinition->getName().' ['.$fieldDefinition->getFieldtype()."]\n";

        if (method_exists($fieldDefinition, 'getFieldDefinitions')) {
            foreach ($fieldDefinition->getFieldDefinitions() as $subDefinition) {
                $text .= $this->buildFieldDefinitionDocBlock($subDefinition, $level + 1);
            }
        }

        return $text;
    }
}
