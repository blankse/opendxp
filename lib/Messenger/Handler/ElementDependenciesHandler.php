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

use OpenDxp\Messenger\ElementDependenciesMessage;
use OpenDxp\Model\DataObject\AbstractObject;
use OpenDxp\Model\Dependency;
use OpenDxp\Model\Document;
use OpenDxp\Model\Element\AbstractElement;
use OpenDxp\Model\Element\Service;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/**
 * @internal
 */
#[AsMessageHandler]
class ElementDependenciesHandler
{
    public function __construct(protected LoggerInterface $logger)
    {
    }

    public function __invoke(ElementDependenciesMessage $message): void
    {
        $element = Service::getElementById($message->getType(), $message->getId());
        if ($element instanceof AbstractElement) {
            $this->saveDependencies($element);
        }
    }

    private function saveDependencies(AbstractElement $element): void
    {
        $hideUnpublished = $this->showUnpublished($element);
        $getInheritedValues = AbstractObject::getGetInheritedValues();
        AbstractObject::setGetInheritedValues(false);

        $id = $element->getId();
        $type = Service::getElementType($element);

        $this->logger->debug(sprintf('Processing dependencies of %s with ID %s ', $type, $id));

        $d = new Dependency();
        $d->setSourceType($type);
        $d->setSourceId($id);

        foreach ($element->resolveDependencies() as $requirement) {
            if ($requirement['id'] == $id && $requirement['type'] == $type) {
                // dont't add a reference to yourself
                continue;
            }

            $d->addRequirement($requirement['id'], $requirement['type']);
        }
        $this->resetHideUnpublished($element, $hideUnpublished);
        AbstractObject::setGetInheritedValues($getInheritedValues);

        $d->save();

    }

    private function showUnpublished(AbstractElement $element): ?bool
    {
        $hideUnpublished = null;
        if ($element instanceof AbstractObject) {
            $hideUnpublished = AbstractObject::getHideUnpublished();
            AbstractObject::setHideUnpublished(false);
        } elseif ($element instanceof Document) {
            $hideUnpublished = Document::doHideUnpublished();
            Document::setHideUnpublished(false);
        }

        return $hideUnpublished;
    }

    private function resetHideUnpublished(AbstractElement $element, ?bool $hideUnpublished): void
    {
        if ($element instanceof AbstractObject) {
            AbstractObject::setHideUnpublished($hideUnpublished);
        } elseif ($element instanceof Document) {
            Document::setHideUnpublished($hideUnpublished);
        }
    }
}
