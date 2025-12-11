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

namespace OpenDxp\Routing;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

interface RouteReferenceInterface
{
    /**
     * Get route name
     */
    public function getRoute(): string;

    /**
     * Get parameters to use when generating the route
     */
    public function getParameters(): array;

    /**
     * Get route type - directly passed to URL generator
     *
     * @see UrlGeneratorInterface
     */
    public function getType(): int;
}
