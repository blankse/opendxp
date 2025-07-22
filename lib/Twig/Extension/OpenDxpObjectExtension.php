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

use OpenDxp\Model\Asset;
use OpenDxp\Model\DataObject;
use OpenDxp\Model\Document;
use OpenDxp\Model\Site;
use OpenDxp\Model\User;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @internal
 */
class OpenDxpObjectExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        // simple object access functions in case documents/assets/objects need to be loaded directly in the template
        return [
            new TwigFunction('opendxp_document', [Document::class, 'getById']),
            new TwigFunction('opendxp_document_by_path', [Document::class, 'getByPath']),
            new TwigFunction('opendxp_site', [Site::class, 'getById']),
            new TwigFunction('opendxp_site_by_root_id', [Site::class, 'getByRootId']),
            new TwigFunction('opendxp_site_by_domain', [Site::class, 'getByDomain']),
            new TwigFunction('opendxp_site_is_request', [Site::class, 'isSiteRequest']),
            new TwigFunction('opendxp_site_current', [Site::class, 'getCurrentSite']),
            new TwigFunction('opendxp_asset', [Asset::class, 'getById']),
            new TwigFunction('opendxp_asset_by_path', [Asset::class, 'getByPath']),
            new TwigFunction('opendxp_object', [DataObject::class, 'getById']),
            new TwigFunction('opendxp_object_by_path', [DataObject::class, 'getByPath']),
            new TwigFunction('opendxp_document_wrap_hardlink', [Document\Hardlink\Service::class, 'wrap']),
            new TwigFunction('opendxp_user', [User::class, 'getById']),
            new TwigFunction('opendxp_object_classificationstore_group', [DataObject\Classificationstore\GroupConfig::class, 'getById']),
            new TwigFunction('opendxp_object_classificationstore_get_field_definition_from_json', [$this, 'getFieldDefinitionFromJson']),
            new TwigFunction('opendxp_object_brick_definition_key', [DataObject\Objectbrick\Definition::class, 'getByKey']),
        ];
    }

    public function getFieldDefinitionFromJson(array|string $definition, string $type): ?DataObject\ClassDefinition\Data
    {
        if (is_json($definition)) {
            $definition = json_decode($definition, true);
        }

        return DataObject\Classificationstore\Service::getFieldDefinitionFromJson($definition, $type);
    }
}
