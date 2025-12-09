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

use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\Adapter\RedisTagAwareAdapter;

/**
 * @group cache.core.redis
 */
class RedisCoreHandlerTest extends AbstractCoreHandlerTest
{
    /**
     * Initializes item pool
     */
    protected function createCachePool(): RedisTagAwareAdapter
    {
        $dsn = getenv('OPENDXP_TEST_REDIS_DSN');
        $client = RedisAdapter::createConnection($dsn);
        $adapter = new RedisTagAwareAdapter($client, '', $this->defaultLifetime);

        return $adapter;
    }
}
