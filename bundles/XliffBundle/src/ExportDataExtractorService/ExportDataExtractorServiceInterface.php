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

namespace OpenDxp\Bundle\XliffBundle\ExportDataExtractorService;

use Exception;
use OpenDxp\Bundle\XliffBundle\AttributeSet\AttributeSet;
use OpenDxp\Bundle\XliffBundle\ExportDataExtractorService\DataExtractor\DataExtractorInterface;
use OpenDxp\Bundle\XliffBundle\TranslationItemCollection\TranslationItem;

interface ExportDataExtractorServiceInterface
{
    /**
     * @param string[] $targetLanguages
     *
     * @throws Exception
     */
    public function extract(TranslationItem $translationItem, string $sourceLanguage, array $targetLanguages): AttributeSet;

    public function registerDataExtractor(string $type, DataExtractorInterface $dataExtractor): ExportDataExtractorServiceInterface;

    /**
     *
     *
     * @throws Exception
     */
    public function getDataExtractor(string $type): DataExtractorInterface;
}
