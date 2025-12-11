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

namespace OpenDxp\Model\Paginator\EventSubscriber;

use Knp\Component\Pager\Event\ItemsEvent;
use OpenDxp\Model\Paginator\PaginateListingInterface;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PaginateListingSubscriber implements EventSubscriberInterface
{
    public function items(ItemsEvent $event): void
    {
        $paginationAdapter = $event->target;

        if ($paginationAdapter instanceof PaginateListingInterface) {
            $items = $paginationAdapter->getItems($event->getOffset(), $event->getLimit());
            $event->count = $paginationAdapter->count();
            $event->items = $items;
            $event->stopPropagation();
        }

        if (!$event->isPropagationStopped()) {
            throw new RuntimeException('Paginator only accepts instances of the type ' .
                PaginateListingInterface::class . ' or types defined here: https://github.com/KnpLabs/KnpPaginatorBundle#controller');
        }
    }

    /**
     * @internal
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'knp_pager.items' => ['items', -5/* other data listeners should be analyzed first*/],
        ];
    }
}
