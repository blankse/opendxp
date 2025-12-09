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

namespace OpenDxp\Bundle\XliffBundle\DependencyInjection\Compiler;

use Exception;
use OpenDxp\Bundle\XliffBundle\ExportDataExtractorService\ExportDataExtractorServiceInterface;
use OpenDxp\Bundle\XliffBundle\ImporterService\ImporterServiceInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @internal
 */
final class TranslationServicesPass implements CompilerPassInterface
{
    /**
     * Registers each service with tag opendxp.translation.data-extractor as data extractor for the translations export data extractor service.
     * Registers each service with tag opendxp.translation.importer as importer for the translations importer service.
     */
    public function process(ContainerBuilder $container): void
    {
        $providers = $container->findTaggedServiceIds('opendxp.translation.data-extractor');

        foreach ($providers as $id => $tags) {
            foreach ($tags as $attributes) {
                if (empty($attributes['type'])) {
                    throw new Exception('service with tag "opendxp.translation.data-extractor" but without type registered');
                }
                $definition = $container->getDefinition(ExportDataExtractorServiceInterface::class);
                $definition->addMethodCall('registerDataExtractor', [$attributes['type'], new Reference($id)]);
            }
        }

        $providers = $container->findTaggedServiceIds('opendxp.translation.importer');

        foreach ($providers as $id => $tags) {
            foreach ($tags as $attributes) {
                if (empty($attributes['type'])) {
                    throw new Exception('service with tag "opendxp.translation.data-extractor" but without type registered');
                }
                $definition = $container->getDefinition(ImporterServiceInterface::class);
                $definition->addMethodCall('registerImporter', [$attributes['type'], new Reference($id)]);
            }
        }
    }
}
