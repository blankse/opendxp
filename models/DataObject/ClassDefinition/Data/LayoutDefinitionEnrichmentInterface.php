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

use Exception;
use OpenDxp\Model\DataObject\Concrete;

interface LayoutDefinitionEnrichmentInterface
{
    /**
     * Override point for enriching the object's layout definition before the layout is returned to the admin interface.
     * An example would the select datatype with a dynamic options provider.
     *
     *
     * @param array<string, mixed> $context additional contextual data like fieldname etc.
     *
     * @return $this
     *
     * @throws Exception
     */
    public function enrichLayoutDefinition(?Concrete $object, array $context = []): static;
}
