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

namespace OpenDxp\Bundle\CoreBundle\EventListener\Traits;

use OpenDxp\Http\Request\Resolver\OpenDxpContextResolver;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @internal
 */
trait OpenDxpContextAwareTrait
{
    private ?OpenDxpContextResolver $OpenDxpContextResolver = null;

    #[Required]
    public function setOpenDxpContextResolver(OpenDxpContextResolver $contextResolver): void
    {
        $this->OpenDxpContextResolver = $contextResolver;
    }

    /**
     * Check if the request matches the given opendxp context (e.g. admin)
     */
    protected function matchesOpenDxpContext(Request $request, array|string $context): bool
    {
        if (null === $this->OpenDxpContextResolver) {
            throw new RuntimeException('Missing opendxp context resolver. Is the listener properly configured?');
        }

        return $this->OpenDxpContextResolver->matchesOpenDxpContext($request, $context);
    }
}
