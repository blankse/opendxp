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

namespace OpenDxp\Bundle\CoreBundle\DependencyInjection\Compiler;

use InvalidArgumentException;
use OpenDxp\Maintenance\Executor;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @internal
 */
final class RegisterMaintenanceTaskPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition(Executor::class)) {
            return;
        }

        $definition = $container->getDefinition(Executor::class);

        foreach ($container->findTaggedServiceIds('opendxp.maintenance.task') as $id => $tags) {
            if (!isset($tags[0]['type'])) {
                throw new InvalidArgumentException('Tagged Maintenance Task `'.$id.'` needs to a `type` attribute.');
            }

            $definition->addMethodCall('registerTask', [$tags[0]['type'], new Reference($id), $tags[0]['messengerMessageClass'] ?? null]);
        }
    }
}
