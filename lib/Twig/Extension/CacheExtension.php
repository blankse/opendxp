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

namespace OpenDxp\Twig\Extension;

use OpenDxp\Cache as CacheManager;
use OpenDxp\Http\Request\Resolver\EditmodeResolver;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @internal
 *
 * @deprecated
 */
class CacheExtension extends AbstractExtension
{
    protected string $key;

    /**
     * @var bool[]
     */
    protected array $captureEnabled = [];

    protected bool $force = false;

    protected ?int $lifetime;

    protected EditmodeResolver $editmodeResolver;

    public function __construct(EditmodeResolver $editmodeResolver)
    {
        $this->editmodeResolver = $editmodeResolver;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('opendxp_cache', [$this, 'init'], ['is_safe' => ['html']]),
        ];
    }

    /**
     *
     * @return $this
     */
    public function init(string $name, int $lifetime = null, bool $force = false): static
    {
        trigger_deprecation(
            'open-dxp/opendxp',
            '11.4',
            '"opendxp_cache" twig extension is deprecated. Use the "opendxpcache" tag instead.'
        );

        $this->key = 'opendxp_viewcache_' . $name;
        $this->force = $force;

        if (!$lifetime) {
            $lifetime = null;
        }

        $this->lifetime = $lifetime;

        return $this;
    }

    public function start(): bool
    {
        if (\OpenDxp\Tool::isFrontendRequestByAdmin() && !$this->force) {
            return false;
        }

        if ($content = CacheManager::load($this->key)) {
            $this->outputContent($content, $this->key, true);

            return true;
        }

        $this->captureEnabled[$this->key] = true;
        ob_start();

        return false;
    }

    public function end(): void
    {
        if ($this->captureEnabled[$this->key] ?? false) {
            $this->captureEnabled[$this->key] = false;

            $tags = ['in_template'];
            if (!$this->lifetime) {
                $tags[] = 'output';
            }

            $content = ob_get_clean();
            $this->saveContentToCache($content, $this->key, $tags);
            $this->outputContent($content, $this->key, false);
        }
    }

    public function stop(): void
    {
        $this->end();
    }

    /**
     * Output the content.
     *
     * @param string $content the content, either rendered or retrieved directly from the cache.
     * @param string $key the cache key
     * @param bool $isLoadedFromCache true if the content origins from the cache and hasn't been created "live".
     */
    protected function outputContent(string $content, string $key, bool $isLoadedFromCache): void
    {
        echo $content;
    }

    /**
     * Save the (rendered) content to to cache. May be overriden to add custom markup / code, or specific tags, etc.
     *
     */
    protected function saveContentToCache(string $content, string $key, array $tags): void
    {
        CacheManager::save($content, $key, $tags, $this->lifetime, 996, true);
    }
}
