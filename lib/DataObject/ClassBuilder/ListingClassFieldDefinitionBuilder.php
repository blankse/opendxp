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

namespace OpenDxp\DataObject\ClassBuilder;

use OpenDxp\Model\DataObject\ClassDefinition;

class ListingClassFieldDefinitionBuilder implements ListingClassFieldDefinitionBuilderInterface
{
    public function buildListingClassFieldDefinition(ClassDefinition $classDefinition, ClassDefinition\Data $fieldDefinition): string
    {
        if ($fieldDefinition instanceof ClassDefinition\Data\Localizedfields) {
            $cd = '';
            foreach ($fieldDefinition->getFieldDefinitions() as $localizedFieldDefinition) {
                $cd .= $localizedFieldDefinition->getFilterCode();
            }

            return $cd;
        }

        if ($fieldDefinition->isFilterable()) {
            return $fieldDefinition->getFilterCode();
        }

        return '';
    }
}
