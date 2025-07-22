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

namespace OpenDxp\Tests\Cache\Core;

use OpenDxp;
use OpenDxp\Tests\Support\Util\TestHelper;
use Symfony\Component\Cache\Adapter\DoctrineDbalAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;

/**
 * @group cache.core.db
 */
class DoctrineDbalCoreHandlerTest extends AbstractCoreHandlerTest
{
    /**
     * Initializes item pool
     *
     */
    protected function createCachePool(): TagAwareAdapter
    {
        TestHelper::checkDbSupport();
        $doctrineDbalAdapter = new DoctrineDbalAdapter(OpenDxp::getContainer()->get('doctrine.dbal.default_connection'), '', $this->defaultLifetime);
        $adapter = new TagAwareAdapter($doctrineDbalAdapter);

        return $adapter;
    }
}
