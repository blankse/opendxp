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

use OpenDxp\Event\DocumentEvents;
use OpenDxp\Twig\Extension\Templating\Placeholder\ContainerService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Handles block state for sub requests (saves parent state and restores it after request completes)
 *
 * @internal
 */
class DocumentRendererListener implements EventSubscriberInterface
{
    public function __construct(protected ContainerService $containerService)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            DocumentEvents::RENDERER_PRE_RENDER => 'onPreRender',
            DocumentEvents::RENDERER_POST_RENDER => 'onPostRender',
        ];
    }

    public function onPreRender(): void
    {
        // when rendering a new document, the index is pushed to create a new, empty context
        $this->containerService->pushIndex();
    }

    public function onPostRender(): void
    {
        $this->containerService->popIndex();
    }
}
