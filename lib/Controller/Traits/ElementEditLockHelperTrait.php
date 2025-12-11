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

namespace OpenDxp\Controller\Traits;

use OpenDxp\Model\Element\Editlock;
use OpenDxp\Model\User;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @internal
 */
trait ElementEditLockHelperTrait
{
    protected function getEditLockResponse(int $id, string $type): JsonResponse
    {
        $editLock = Editlock::getByElement($id, $type);
        $user = User::getById($editLock->getUserId());

        $editLock = $editLock->getObjectVars();
        unset($editLock['sessionId']);

        if ($user) {
            $editLock['user'] = [
                'name' => $user->getName(),
            ];
        }

        return $this->adminJson([
            'editlock' => $editLock,
        ]);
    }
}
