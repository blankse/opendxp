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

namespace OpenDxp\Bundle\CoreBundle\EventListener\Traits;

use OpenDxp\Http\Request\Resolver\StaticPageResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @internal
 */
trait StaticPageContextAwareTrait
{
    private ?StaticPageResolver $staticPageResolver = null;

    #[Required]
    public function setStaticPageResolver(StaticPageResolver $staticPageResolver): void
    {
        $this->staticPageResolver = $staticPageResolver;
    }

    /**
     * Check if the request has static page context
     *
     *
     */
    protected function matchesStaticPageContext(Request $request): bool
    {
        return $this->staticPageResolver->hasStaticPageContext($request);
    }
}
