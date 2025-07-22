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

namespace OpenDxp\Document\Renderer;

use Exception;
use OpenDxp\Event\DocumentEvents;
use OpenDxp\Event\Model\DocumentEvent;
use OpenDxp\Http\RequestHelper;
use OpenDxp\Localization\LocaleServiceInterface;
use OpenDxp\Model\Document;
use OpenDxp\Model\Site;
use OpenDxp\Routing\Dynamic\DocumentRouteHandler;
use OpenDxp\Templating\Renderer\ActionRenderer;
use OpenDxp\Tool;
use OpenDxp\Tool\Frontend;
use OpenDxp\Twig\Extension\Templating\Placeholder\ContainerService;
use Symfony\Component\HttpKernel\Fragment\FragmentRendererInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Service\Attribute\Required;

class DocumentRenderer implements DocumentRendererInterface
{
    private RequestHelper $requestHelper;

    private ActionRenderer $actionRenderer;

    private FragmentRendererInterface $fragmentRenderer;

    private DocumentRouteHandler $documentRouteHandler;

    private EventDispatcherInterface $eventDispatcher;

    private LocaleServiceInterface $localeService;

    public function __construct(
        RequestHelper $requestHelper,
        ActionRenderer $actionRenderer,
        FragmentRendererInterface $fragmentRenderer,
        DocumentRouteHandler $documentRouteHandler,
        EventDispatcherInterface $eventDispatcher,
        LocaleServiceInterface $localeService
    ) {
        $this->requestHelper = $requestHelper;
        $this->actionRenderer = $actionRenderer;
        $this->fragmentRenderer = $fragmentRenderer;
        $this->documentRouteHandler = $documentRouteHandler;
        $this->eventDispatcher = $eventDispatcher;
        $this->localeService = $localeService;
    }

    #[Required]
    public function setContainerService(ContainerService $containerService): void
    {
        // we have to ensure that the ContainerService was initialized at the time this service is created
        // this is necessary, since the ContainerService registers a listener for DocumentEvents::RENDERER_PRE_RENDER
        // which wouldn't be called if the ContainerService would be lazy initialized when the first
        // placeholder service/templating helper is used during the rendering process
    }

    public function render(Document\PageSnippet $document, array $attributes = [], array $query = [], array $options = []): string
    {
        $this->eventDispatcher->dispatch(
            new DocumentEvent($document, $attributes),
            DocumentEvents::RENDERER_PRE_RENDER
        );

        // add document route to request if no route is set
        // this is needed for logic relying on the current route (e.g. OpenDxpUrl helper)
        if (!isset($attributes['_route'])) {
            $route = $this->documentRouteHandler->buildRouteForDocument($document);
            if (null !== $route) {
                $attributes['_route'] = $route->getRouteKey();
            }
        }

        try {
            $request = $this->requestHelper->getCurrentRequest();
        } catch (Exception $e) {

            $host = null;
            if ($site = Frontend::getSiteForDocument($document)) {
                Site::setCurrentSite($site);
                $host = $site->getMainDomain();
            } elseif ($systemMainDomain = Tool::getHostname()) {
                $host = $systemMainDomain;
            }

            $request = $this->requestHelper->createRequestWithContext(host: $host);
        }

        if ($attributes['opendxp_static_page_generator'] ?? false) {
            $headers = \OpenDxp\Config::getSystemConfiguration('documents')['static_page_generator']['headers'];
            foreach ($headers as $header) {
                $request->headers->set($header['name'], $header['value']);
            }
        }

        $documentLocale = $document->getProperty('language');
        $tempLocale = $this->localeService->getLocale();
        if ($documentLocale) {
            $this->localeService->setLocale($documentLocale);
            $request->setLocale($documentLocale);
        }

        $uri = $this->actionRenderer->createDocumentReference($document, $attributes, $query);
        $response = $this->fragmentRenderer->render($uri, $request, $options);

        $this->localeService->setLocale($tempLocale);

        $this->eventDispatcher->dispatch(
            new DocumentEvent($document, $attributes),
            DocumentEvents::RENDERER_POST_RENDER
        );

        return $response->getContent();
    }
}
