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
 * @copyright  Modification Copyright (c) OpenDXP (https://www.opendxp.ch)
 * @license    https://www.gnu.org/licenses/gpl-3.0.html  GNU General Public License version 3 (GPLv3)
 */

namespace OpenDxp\Extension\Document\Areabrick;

interface AreabrickManagerInterface
{
    /**
     * Registers an areabrick on the manager
     */
    public function register(string $id, AreabrickInterface $brick): void;

    /**
     * Registers a lazy loaded area brick service on the manager
     */
    public function registerService(string $id, string $serviceId): void;

    /**
     * Fetches a brick by ID
     */
    public function getBrick(string $id): AreabrickInterface;

    /**
     * Lists all registered areabricks indexed by ID. Will implicitely load all bricks registered as service.
     *
     * @return AreabrickInterface[]
     */
    public function getBricks(): array;

    /**
     * Lists all registered areabrick IDs
     */
    public function getBrickIds(): array;
}
