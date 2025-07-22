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

namespace OpenDxp\Bundle\SeoBundle\Maintenance;

use OpenDxp\Bundle\SeoBundle\Model\Redirect;
use OpenDxp\Maintenance\TaskInterface;

/**
 * @internal
 */
class RedirectCleanupTask implements TaskInterface
{
    public function execute(): void
    {
        $list = new Redirect\Listing();
        $list->setCondition('active = 1 AND expiry < '.time()." AND expiry IS NOT NULL AND expiry != ''");
        $list->load();

        foreach ($list->getRedirects() as $redirect) {
            $redirect->setActive(false);
            $redirect->save();
        }
    }
}
