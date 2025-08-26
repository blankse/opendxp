<?php

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

namespace OpenDxp\Controller;

use OpenDxp\Logger;
use OpenDxp\Model\User;
use OpenDxp\Security\User\TokenStorageUserResolver;
use OpenDxp\Security\User\User as UserProxy;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Contracts\Service\Attribute\Required;
use Throwable;

abstract class UserAwareController extends AbstractController
{
    protected TokenStorageUserResolver $tokenResolver;

    #[Required]
    public function setTokenResolver(TokenStorageUserResolver $tokenResolver): void
    {
        $this->tokenResolver = $tokenResolver;
    }

    /**
     * Get user from user proxy object which is registered on security component
     */
    protected function getOpenDxpUser(bool $proxyUser = false): UserProxy|User|null
    {
        if ($proxyUser) {
            return $this->tokenResolver->getUserProxy();
        }

        return $this->tokenResolver->getUser();
    }

    /**
     * Check user permission
     *
     * @throws AccessDeniedHttpException
     */
    protected function checkPermission(string $permission): void
    {
        if (!$this->getOpenDxpUser() || !$this->getOpenDxpUser()->isAllowed($permission)) {
            Logger::error(
                'User {user} attempted to access {permission}, but has no permission to do so',
                [
                    'user' => $this->getOpenDxpUser()?->getName(),
                    'permission' => $permission,
                ]
            );

            throw $this->createAccessDeniedHttpException();
        }
    }

    protected function createAccessDeniedHttpException(
        string $message = 'Access Denied.',
        Throwable $previous = null,
        int $code = 0,
        array $headers = []
    ): AccessDeniedHttpException {
        // $headers parameter not supported by Symfony 3.4
        return new AccessDeniedHttpException($message, $previous, $code, $headers);
    }

    /**
     * @param string[] $permissions
     */
    protected function checkPermissionsHasOneOf(array $permissions): void
    {
        $allowed = false;
        $permission = null;
        foreach ($permissions as $permission) {
            if ($this->getOpenDxpUser()->isAllowed($permission)) {
                $allowed = true;

                break;
            }
        }

        if (!$this->getOpenDxpUser() || !$allowed) {
            Logger::error(
                'User {user} attempted to access {permission}, but has no permission to do so',
                [
                    'user' => $this->getOpenDxpUser()->getName(),
                    'permission' => $permission,
                ]
            );

            throw new AccessDeniedHttpException('Attempt to access ' . $permission . ', but has no permission to do so.');
        }
    }

    /**
     * Check permission against all controller actions. Can optionally exclude a list of actions.
     */
    protected function checkActionPermission(ControllerEvent $event, string $permission, array $unrestrictedActions = []): void
    {
        $actionName = null;
        $controller = $event->getController();

        if (is_array($controller) && count($controller) === 2 && is_string($controller[1])) {
            $actionName = $controller[1];
        }

        if (null === $actionName || !in_array($actionName, $unrestrictedActions)) {
            $this->checkPermission($permission);
        }
    }
}
