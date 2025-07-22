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

namespace OpenDxp\Messenger\Handler;

use Exception;
use OpenDxp\Logger;
use OpenDxp\Messenger\GeneratePagePreviewMessage;
use OpenDxp\Model\Document\Service;
use Psr\Log\LoggerInterface;

/**
 * @internal
 */
class GeneratePagePreviewHandler
{
    public function __construct(protected LoggerInterface $logger)
    {
    }

    public function __invoke(GeneratePagePreviewMessage $message): void
    {
        try {
            Service::generatePagePreview($message->getPageId(), null, $message->getHostUrl());
        } catch (Exception $e) {
            Logger::err(sprintf('Unable to generate preview of document: %s, reason: %s ', $message->getPageId(), $e->getMessage()));
        }
    }
}
