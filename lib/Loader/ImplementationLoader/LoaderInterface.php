<?php

declare(strict_types = 1);

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

namespace OpenDxp\Loader\ImplementationLoader;

/**
 * @internal
 */
interface LoaderInterface
{
    /**
     * Checks if implementation is supported
     */
    public function supports(string $name): bool;

    /**
     * Builds an implementation instance
     */
    public function build(string $name, array $params = []): mixed;
}
