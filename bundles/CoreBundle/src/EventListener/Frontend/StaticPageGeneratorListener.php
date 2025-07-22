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

namespace OpenDxp\Bundle\CoreBundle\EventListener\Frontend;

use DateTimeInterface;
use Exception;
use OpenDxp\Bundle\CoreBundle\EventListener\Traits\OpenDxpContextAwareTrait;
use OpenDxp\Bundle\CoreBundle\EventListener\Traits\StaticPageContextAwareTrait;
use OpenDxp\Config;
use OpenDxp\Document\StaticPageGenerator;
use OpenDxp\Event\DocumentEvents;
use OpenDxp\Event\Model\DocumentEvent;
use OpenDxp\Http\Request\Resolver\DocumentResolver;
use OpenDxp\Http\Request\Resolver\OpenDxpContextResolver;
use OpenDxp\Http\RequestHelper;
use OpenDxp\Logger;
use OpenDxp\Model\Document\Page;
use OpenDxp\Model\Document\PageSnippet;
use OpenDxp\Model\Site;
use OpenDxp\Tool\Storage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @internal
 */
class StaticPageGeneratorListener implements EventSubscriberInterface
{
    use OpenDxpContextAwareTrait;
    use StaticPageContextAwareTrait;

    public function __construct(
        protected StaticPageGenerator $staticPageGenerator,
        protected DocumentResolver $documentResolver,
        protected RequestHelper $requestHelper,
        private Config $config
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            DocumentEvents::POST_ADD => 'onPostAddUpdateDeleteDocument',
            DocumentEvents::POST_DELETE => 'onPostAddUpdateDeleteDocument',
            DocumentEvents::POST_UPDATE => 'onPostAddUpdateDeleteDocument',
            KernelEvents::REQUEST => ['onKernelRequest', 510], //this must run before targeting listener
            KernelEvents::RESPONSE => ['onKernelResponse', -120], //this must run after code injection listeners
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        if (!$this->checkValidRequest($request)) {
            return;
        }

        $config = $this->config['documents'];
        if (!$config['static_page_router']['enabled']) {
            return;
        }

        $routePattern = $config['static_page_router']['route_pattern'];
        if (!empty($routePattern) && !@preg_match($routePattern, $request->getPathInfo())) {
            return;
        }

        $storage = Storage::get('document_static');

        try {
            $path = '';
            $filename = urldecode($request->getPathInfo());

            if (Site::isSiteRequest()) {
                if ($request->getPathInfo() === '/') {
                    $filename = '/' . Site::getCurrentSite()->getRootDocument()->getKey();
                } else {
                    $path = Site::getCurrentSite()->getRootPath();
                }
            }
            $filename = $path .  $filename  . '.html';

            if ($storage->fileExists($filename)) {
                $content = $storage->read($filename);
                $date = date(DateTimeInterface::ATOM, $storage->lastModified($filename));

                $reponse = new Response(
                    $content, Response::HTTP_OK, [
                    'Content-Type' => 'text/html',
                    'X-OpenDxp-Static-Page-Last-Modified' => $date,
                ]
                );

                $event->setResponse($reponse);
            }
        } catch (Exception $e) {
            Logger::error($e->getMessage());
        }
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        if (!$this->checkValidRequest($request)) {
            return;
        }

        //return if request is from StaticPageGenerator
        if ($request->attributes->has('opendxp_static_page_generator')) {
            return;
        }

        // only inject analytics code on non-admin requests
        if (!$this->matchesOpenDxpContext($request, OpenDxpContextResolver::CONTEXT_DEFAULT)
            && !$this->matchesStaticPageContext($request)) {
            return;
        }

        $document = $this->documentResolver->getDocument();

        if ($document instanceof Page && $document->getStaticGeneratorEnabled()) {
            $response = $event->getResponse()->getContent();
            $this->staticPageGenerator->generate($document, ['response' => $response]);
        }
    }

    public function onPostAddUpdateDeleteDocument(DocumentEvent $e): void
    {
        $document = $e->getDocument();

        if ($e->hasArgument('saveVersionOnly') || $e->hasArgument('autoSave')) {
            return;
        }

        if ($document instanceof PageSnippet) {
            try {
                if ($document->getStaticGeneratorEnabled()
                    || $this->staticPageGenerator->pageExists($document)) {
                    $this->staticPageGenerator->remove($document);
                }
            } catch (Exception $e) {
                Logger::error((string) $e);

                return;
            }
        }
    }

    private function checkValidRequest(Request $request): bool
    {
        if ($this->requestHelper->isFrontendRequestByAdmin($request)
            || $request->isXmlHttpRequest()
            || $request->getMethod() !== 'GET'
            || !in_array('text/html', $request->getAcceptableContentTypes())) {
            return false;
        }

        return true;
    }
}
