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

namespace OpenDxp\Model\DataObject\ClassDefinition\Data;

interface FieldDefinitionEnrichmentInterface
{
    /**
     * If in admin mode this method can be implemented to change the fielddefinition whenever
     * getFieldDefinition() get called on the data type.
     * One example purpose is to populate or change dynamic settings like the options for select and multiselect fields.
     * The context param contains contextual information about the container, the field name, etc ...
     *
     *
     * @return $this
     */
    public function enrichFieldDefinition(array $context = []): static;
}
