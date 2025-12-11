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

namespace OpenDxp\Helper;

use InvalidArgumentException;

final class EncoreHelper
{
    public static function getBuildPathsFromEntrypoints(string $entrypointsFile, string $type = 'js'): array
    {
        if (!file_exists($entrypointsFile)) {
            throw new InvalidArgumentException(sprintf('The file "%s" does not exist.', $entrypointsFile));
        }

        $entrypointsContent = file_get_contents($entrypointsFile);
        $entrypoints = json_decode($entrypointsContent, true, flags: JSON_THROW_ON_ERROR)['entrypoints'];

        $paths = [];
        foreach ($entrypoints as $entrypoint) {
            $paths[] = $entrypoint[$type] ?? [];
        }

        return array_merge(...$paths);
    }
}
