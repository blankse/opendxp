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

namespace OpenDxp\Bundle\SeoBundle\Sitemap\Element;

use OpenDxp\Model\Element\ElementInterface;
use Presta\SitemapBundle\Sitemap\Url\Url;

interface ProcessorInterface
{
    /**
     * Processes an URL. The processor is expected to return the same or a new URL instance or null
     */
    public function process(Url $url, ElementInterface $element, GeneratorContextInterface $context): ?Url;
}
