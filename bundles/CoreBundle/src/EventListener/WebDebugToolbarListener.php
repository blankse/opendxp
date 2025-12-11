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

namespace OpenDxp\Bundle\CoreBundle\EventListener;

use OpenDxp\Http\RequestHelper;
use OpenDxp\Http\RequestMatcherFactory;
use Symfony\Bundle\WebProfilerBundle\EventListener\WebDebugToolbarListener as SymfonyWebDebugToolbarListener;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\ChainRequestMatcher;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Disables the web debug toolbar for frontend requests by admins (iframes inside admin interface)
 *
 * @internal
 */
class WebDebugToolbarListener implements EventSubscriberInterface
{
    protected ?array $excludeMatchers = null;

    public function __construct(
        protected RequestHelper $requestHelper,
        protected RequestMatcherFactory $requestMatcherFactory,
        protected ?SymfonyWebDebugToolbarListener $debugToolbarListener,
        protected EventDispatcherInterface $eventDispatcher,
        protected array $excludeRoutes
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelResponse', -118],
        ];
    }

    public function onKernelResponse(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        // do not show toolbar on frontend-admin requests
        if ($this->requestHelper->isFrontendRequestByAdmin($request)) {
            $this->disableWebDebugToolbar();
        }

        // do not show toolbar on excluded routes (opendxp.web_profiler.toolbar.excluded_routes config entry)
        /** @var array $excludeMatcher */
        foreach ($this->getExcludeMatchers() as $excludeMatcher) {
            $chainRequestMatcher = new ChainRequestMatcher($excludeMatcher);
            if ($chainRequestMatcher->matches($request)) {
                $this->disableWebDebugToolbar();
            }
        }
    }

    protected function getExcludeMatchers(): array
    {
        if (null === $this->excludeMatchers) {
            $this->excludeMatchers = $this->requestMatcherFactory->buildRequestMatchers($this->excludeRoutes);
        }

        return $this->excludeMatchers;
    }

    protected function disableWebDebugToolbar(): void
    {
        if ($this->debugToolbarListener) {
            $this->eventDispatcher->removeSubscriber($this->debugToolbarListener);
        }
    }
}
