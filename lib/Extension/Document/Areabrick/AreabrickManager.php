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

use OpenDxp\Extension\Document\Areabrick\Exception\BrickNotFoundException;
use OpenDxp\Extension\Document\Areabrick\Exception\ConfigurationException;
use Psr\Container\ContainerInterface;

/**
 * @internal
 */
class AreabrickManager implements AreabrickManagerInterface
{
    protected ContainerInterface $container;

    /**
     * @var AreabrickInterface[]
     */
    protected array $bricks = [];

    protected array $brickServiceIds = [];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function register(string $id, AreabrickInterface $brick): void
    {
        if (array_key_exists($id, $this->bricks)) {
            throw new ConfigurationException(sprintf(
                'Areabrick %s is already registered as %s (trying to add %s)',
                $id,
                get_class($this->bricks[$id]),
                get_class($brick)
            ));
        }

        if (array_key_exists($id, $this->brickServiceIds)) {
            throw new ConfigurationException(sprintf(
                'Areabrick %s is already registered as service %s (trying to add %s)',
                $id,
                $this->brickServiceIds[$id],
                get_class($brick)
            ));
        }

        $brick->setId($id);

        $this->bricks[$id] = $brick;
    }

    public function registerService(string $id, string $serviceId): void
    {
        if (array_key_exists($id, $this->bricks)) {
            throw new ConfigurationException(sprintf(
                'Areabrick %s is already registered as %s (trying to add service %s)',
                $id,
                get_class($this->bricks[$id]),
                $serviceId
            ));
        }

        if (array_key_exists($id, $this->brickServiceIds)) {
            throw new ConfigurationException(sprintf(
                'Areabrick %s is already registered as service %s (trying to add service %s)',
                $id,
                $this->brickServiceIds[$id],
                $serviceId
            ));
        }

        $this->brickServiceIds[$id] = $serviceId;
    }

    public function getBrick(string $id): AreabrickInterface
    {
        $brick = null;
        if (isset($this->bricks[$id])) {
            $brick = $this->bricks[$id];
        } else {
            $brick = $this->loadServiceBrick($id);
        }

        if (null === $brick) {
            throw new BrickNotFoundException(sprintf('Areabrick %s is not registered', $id));
        }

        return $brick;
    }

    public function getBricks(): array
    {
        if (count($this->brickServiceIds) > 0) {
            $this->loadServiceBricks();
        }

        return $this->bricks;
    }

    public function getBrickIds(): array
    {
        $ids = array_merge(
            array_keys($this->bricks),
            array_keys($this->brickServiceIds)
        );

        return $ids;
    }

    /**
     * Loads brick from container
     *
     *
     */
    protected function loadServiceBrick(string $id): ?AreabrickInterface
    {
        if (!isset($this->brickServiceIds[$id])) {
            return null;
        }

        $serviceId = $this->brickServiceIds[$id];
        if (!$this->container->has($id)) {
            throw new ConfigurationException(sprintf(
                'Definition for areabrick %s (defined as service %s) does not exist',
                $id,
                $serviceId
            ));
        }

        $brick = $this->container->get($id);
        if (!($brick instanceof AreabrickInterface)) {
            throw new ConfigurationException(sprintf(
                'Definition for areabrick %s (defined as service %s) does not implement AreabrickInterface (got %s)',
                $id,
                $serviceId,
                is_object($brick) ? get_class($brick) : gettype($brick)
            ));
        }

        $brick->setId($id);

        // move brick to map of loaded bricks
        unset($this->brickServiceIds[$id]);
        $this->bricks[$id] = $brick;

        return $brick;
    }

    /**
     * Loads all brick instances registered as service definitions
     */
    protected function loadServiceBricks(): void
    {
        foreach ($this->brickServiceIds as $id => $serviceId) {
            $this->loadServiceBrick($id);
        }
    }
}
