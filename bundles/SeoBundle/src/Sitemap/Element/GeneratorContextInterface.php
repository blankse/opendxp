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

use IteratorAggregate;
use Presta\SitemapBundle\Service\UrlContainerInterface;

/**
 * Context which is passed to every filter/processor
 */
interface GeneratorContextInterface extends IteratorAggregate, \Countable
{
    public function getUrlContainer(): UrlContainerInterface;

    public function getSection(): ?string;

    public function all(): array;

    public function keys(): array;

    public function get(int|string $key, mixed $default = null): mixed;

    public function has(int|string $key): bool;
}
