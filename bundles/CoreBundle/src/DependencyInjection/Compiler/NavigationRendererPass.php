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

namespace OpenDxp\Bundle\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Adds tagged navigation renderers to navigation helper
 *
 * @internal
 */
final class NavigationRendererPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $taggedServices = $container->findTaggedServiceIds('opendxp.navigation.renderer');

        $map = [];
        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $tag) {
                $alias = null;
                if (isset($tag['alias']) && !empty($tag['alias'])) {
                    $alias = (string)$tag['alias'];
                }

                if (!$alias) {
                    throw new InvalidConfigurationException(sprintf(
                        'Missing "alias" attribute on navigtion renderer tag for service "%s"',
                        $id
                    ));
                }

                $map[$alias] = new Reference($id);
            }
        }

        $locatorDefinition = $container->findDefinition('opendxp.templating.navigation.renderer_locator');
        $locatorDefinition->setArgument(0, $map);
    }
}
