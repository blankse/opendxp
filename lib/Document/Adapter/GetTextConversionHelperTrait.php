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

namespace OpenDxp\Document\Adapter;

use OpenDxp\Model\Asset\Document;

trait GetTextConversionHelperTrait
{
    public function getText(?int $page = null, ?Document $asset = null, ?string $path = null): mixed
    {
        if (!$asset && $this->asset) {
            $asset = $this->asset;
        }

        $filename = $path ?: $asset->getFilename();

        // if asset is pdf extract via ghostscript
        if (parent::isFileTypeSupported($filename)) {
            return parent::getText($page, $asset, $path);
        }

        if ($this->isFileTypeSupported($filename)) {
            return parent::convertPdfToText($page, static::getLocalFileFromStream($this->getPdf($asset)));
        }

        return '';
    }
}
