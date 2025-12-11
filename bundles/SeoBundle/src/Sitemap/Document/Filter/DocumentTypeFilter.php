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

namespace OpenDxp\Bundle\SeoBundle\Sitemap\Document\Filter;

use OpenDxp\Bundle\SeoBundle\Sitemap\Element\FilterInterface;
use OpenDxp\Bundle\SeoBundle\Sitemap\Element\GeneratorContextInterface;
use OpenDxp\Model\Document;
use OpenDxp\Model\Element\ElementInterface;

class DocumentTypeFilter implements FilterInterface
{
    private array $documentTypes = [
        'page',
        'link',
        'hardlink',
    ];

    private array $containerTypes = [
        'page',
        'folder',
        'link',
        'hardlink',
    ];

    public function __construct(?array $documentTypes = null, ?array $containerTypes = null)
    {
        if (null !== $documentTypes) {
            $this->documentTypes = $documentTypes;
        }

        if (null !== $containerTypes) {
            $this->containerTypes = $containerTypes;
        }
    }

    public function canBeAdded(ElementInterface $element, GeneratorContextInterface $context): bool
    {
        if (!$element instanceof Document || $element instanceof Document\Hardlink\Wrapper\WrapperInterface) {
            return false;
        }

        return in_array($element->getType(), $this->documentTypes);
    }

    public function handlesChildren(ElementInterface $element, GeneratorContextInterface $context): bool
    {
        if (!$element instanceof Document) {
            return false;
        }

        return in_array($element->getType(), $this->containerTypes);
    }
}
