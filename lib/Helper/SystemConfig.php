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

use OpenDxp\Config\LocationAwareConfigRepository;

/**
 * @internal
 */
class SystemConfig
{
    public static function getConfigDataByKey(LocationAwareConfigRepository $repository, string $key): array
    {
        $config = [];
        $configKey = $repository->loadConfigByKey(($key));

        if (isset($configKey[0])) {
            $config = $configKey[0];
            $config['writeable'] = $repository->isWriteable();
        }

        return $config;
    }
}
