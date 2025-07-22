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

namespace OpenDxp\Twig\Extension\Templating\Placeholder;

/**
 * adds cache buster functionality to placeholder extension
 */
abstract class CacheBusterAware extends AbstractExtension
{
    protected bool $cacheBuster = true;

    /**
     * prepares entries with cache buster prefix
     */
    abstract protected function prepareEntries(): void;

    public function isCacheBuster(): bool
    {
        return $this->cacheBuster;
    }

    public function setCacheBuster(bool $cacheBuster): void
    {
        $this->cacheBuster = $cacheBuster;
    }
}
