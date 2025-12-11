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

use Exception;
use OpenDxp;
use OpenDxp\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * @internal
 */
trait StopMessengerWorkersTrait
{
    protected function stopMessengerWorkers(): void
    {
        $app = new Application(OpenDxp::getKernel());
        $app->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'messenger:stop-workers',
            '--no-ansi' => null,
            '--no-interaction' => null,
            '--ignore-maintenance-mode' => null,
        ]);

        $output = new BufferedOutput();
        $return = $app->run($input, $output);

        if (0 !== $return) {
            // return the output, don't use if you used NullOutput()
            $content = $output->fetch();

            throw new Exception('Running messenger:stop-workers failed, output was: ' . $content);
        }
    }
}
