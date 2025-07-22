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

namespace OpenDxp\Bundle\XliffBundle\ExportService;

use Exception;
use OpenDxp\Bundle\XliffBundle\ExportService\Exporter\ExporterInterface;
use OpenDxp\Bundle\XliffBundle\TranslationItemCollection\TranslationItemCollection;

interface ExportServiceInterface
{
    /**
     *
     *
     * @throws Exception
     */
    public function exportTranslationItems(TranslationItemCollection $translationItems, string $sourceLanguage, array $targetLanguages, string $exportId = null): string;

    public function getTranslationExporter(): ExporterInterface;
}
