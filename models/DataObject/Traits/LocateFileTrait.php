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

/**
 * @internal
 */
trait LocateFileTrait
{
    protected function locateDefinitionFile(string $key, string $pathTemplate): string
    {
        $customFile = sprintf('%s/' . $pathTemplate, OPENDXP_CUSTOM_CONFIGURATION_CLASS_DEFINITION_DIRECTORY, $key);

        if (is_file($customFile)) {
            return $customFile;
        }

        return sprintf('%s/' . $pathTemplate, OPENDXP_CLASS_DEFINITION_DIRECTORY, $key);
    }

    protected function locateFile(string $key, string $pathTemplate): string
    {
        $customFile = sprintf('%s/' . $pathTemplate, OPENDXP_CUSTOM_CONFIGURATION_CLASS_DEFINITION_DIRECTORY, $key);

        if (is_file($customFile)) {
            return $customFile;
        }

        return sprintf('%s/' . $pathTemplate, OPENDXP_CLASS_DIRECTORY, $key);
    }
}
