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

namespace OpenDxp\Bundle\CoreBundle\Command\Definition\Import;

use OpenDxp\Model\DataObject\ClassDefinition\Service;
use OpenDxp\Model\DataObject\Objectbrick\Definition;
use OpenDxp\Model\ModelInterface;

/**
 * @internal
 */
class ObjectBrickCommand extends AbstractStructureImportCommand
{
    /**
     * Get type
     */
    protected function getType(): string
    {
        return 'ObjectBrick';
    }

    /**
     * Get definition name from filename (e.g. class_Customer_export.json -> Customer)
     */
    protected function getDefinitionName(string $filename): ?string
    {
        $parts = [];
        if (1 === preg_match('/^objectbrick_(.*)_export\.json$/', $filename, $parts)) {
            return $parts[1];
        }

        return null;
    }

    /**
     * Try to load definition by name
     */
    protected function loadDefinition(string $name): ?ModelInterface
    {
        return Definition::getByKey($name);
    }

    /**
     * Create a new definition
     */
    protected function createDefinition(string $name): Definition
    {
        $definition = new Definition();
        $definition->setKey($name);

        return $definition;
    }

    /**
     * Process import
     */
    protected function import(ModelInterface $definition, string $json): bool
    {
        if (!$definition instanceof Definition) {
            return false;
        }

        return Service::importObjectBrickFromJson($definition, $json);
    }
}
