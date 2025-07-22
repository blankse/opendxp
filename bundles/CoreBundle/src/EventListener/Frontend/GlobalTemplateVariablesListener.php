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

use Exception;
use OpenDxp\Bundle\CoreBundle\EventListener\Traits\OpenDxpContextAwareTrait;
use OpenDxp\Http\Request\Resolver\DocumentResolver;
use OpenDxp\Http\Request\Resolver\EditmodeResolver;
use OpenDxp\Http\Request\Resolver\OpenDxpContextResolver;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

/**
 * @internal
 */
class GlobalTemplateVariablesListener implements EventSubscriberInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;
    use OpenDxpContextAwareTrait;

    protected array $globalsStack = [];

    public function __construct(
        protected DocumentResolver $documentResolver,
        protected EditmodeResolver $editmodeResolver,
        protected Environment $twig
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => ['onKernelController', 15], // has to be after DocumentFallbackListener
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $request = $event->getRequest();
        if (!$this->matchesOpenDxpContext($request, OpenDxpContextResolver::CONTEXT_DEFAULT)) {
            return;
        }

        $globals = $this->twig->getGlobals();

        try {
            // it could be the case that the Twig environment is already initialized at this point
            // then it's not possible anymore to add globals
            $this->twig->addGlobal('document', $this->documentResolver->getDocument($request));
            $this->twig->addGlobal('editmode', $this->editmodeResolver->isEditmode($request));
            $this->globalsStack[] = $globals;
        } catch (Exception) {
            $this->globalsStack[] = false;
        }
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$this->matchesOpenDxpContext($event->getRequest(), OpenDxpContextResolver::CONTEXT_DEFAULT)) {
            return;
        }

        if (count($this->globalsStack)) {
            $globals = array_pop($this->globalsStack);
            if ($globals !== false) {
                $this->twig->addGlobal('document', $globals['document'] ?? null);
                $this->twig->addGlobal('editmode', $globals['editmode'] ?? false);
            }
        }
    }
}
