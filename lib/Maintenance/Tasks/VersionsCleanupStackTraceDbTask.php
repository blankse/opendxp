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

namespace OpenDxp\Maintenance\Tasks;

use OpenDxp\Db;
use OpenDxp\Maintenance\TaskInterface;

/**
 * @internal
 */
class VersionsCleanupStackTraceDbTask implements TaskInterface
{
    public function execute(): void
    {
        Db::get()->executeStatement(
            'UPDATE versions SET stackTrace = NULL WHERE date < ? AND stackTrace IS NOT NULL',
            [time() - 86400 * 7]
        );
    }
}
