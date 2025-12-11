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

namespace OpenDxp\Messenger\Handler;

trait HandlerHelperTrait
{
    protected function filterUnique(array $jobs, callable $callback): array
    {
        $filteredJobs = [];
        foreach ($jobs as [$message, $ack]) {
            $key = $callback($message);
            if (isset($filteredJobs[$key])) {
                $ack->ack($message);
            } else {
                $filteredJobs[$key] = [$message, $ack];
            }
        }

        return $filteredJobs;
    }
}
