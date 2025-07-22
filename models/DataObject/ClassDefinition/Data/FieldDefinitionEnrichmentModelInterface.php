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

namespace OpenDxp\Model\DataObject\ClassDefinition\Data;

use OpenDxp\Model\DataObject\ClassDefinition\Data;

/**
 * See FieldDefinitionEnrichmentModelTrait for implementation/examples
 */
interface FieldDefinitionEnrichmentModelInterface
{
    /**
     * Set values for $context array (if any) and call enrichFieldDefinition on $fieldDefinition.
     */
    public function doEnrichFieldDefinition(Data $fieldDefinition, array $context = []): Data;

    /**
     * Add Data $data to the fieldDefinition collection
     *
     * @return $this
     */
    public function addFieldDefinition(string $key, Data $data): static;

    /**
     * Get Data $data from collection if available
     */
    public function getFieldDefinition(string $key, array $context = []): ?Data;

    /**
     * Get all available fieldDefinitions
     *
     * @return array<string, Data>
     */
    public function getFieldDefinitions(array $context = []): array;

    /**
     * Set fieldDefinition collection
     *
     * @param array<string, Data>|null $fieldDefinitions
     *
     * @return $this
     */
    public function setFieldDefinitions(?array $fieldDefinitions): static;
}
