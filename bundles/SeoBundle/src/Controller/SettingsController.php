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

namespace OpenDxp\Bundle\SeoBundle\Controller;

use OpenDxp\Bundle\SeoBundle\Config;
use OpenDxp\Controller\Traits\JsonHelperTrait;
use OpenDxp\Controller\UserAwareController;
use OpenDxp\Model\Tool\SettingsStore;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class SettingsController extends UserAwareController
{
    use JsonHelperTrait;

    #[Route('/robots-txt', name: 'opendxp_bundle_seo_settings_robotstxtget', methods: ['GET'])]
    public function robotsTxtGetAction(): JsonResponse
    {
        $this->checkPermission('robots.txt');

        $config = Config::getRobotsConfig();

        return $this->jsonResponse([
            'success' => true,
            'data' => $config,
            'onFileSystem' => file_exists(OPENDXP_WEB_ROOT . '/robots.txt'),
        ]);
    }

    #[Route('/robots-txt', name: 'opendxp_bundle_seo_settings_robotstxtput', methods: ['PUT'])]
    public function robotsTxtPutAction(Request $request): JsonResponse
    {
        $this->checkPermission('robots.txt');

        $values = $request->request->all('data');

        foreach ($values as $siteId => $robotsContent) {
            SettingsStore::set('robots.txt-' . $siteId, $robotsContent, SettingsStore::TYPE_STRING, 'robots.txt');
        }

        return $this->jsonResponse([
            'success' => true,
        ]);
    }
}
