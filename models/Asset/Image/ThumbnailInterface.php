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

namespace OpenDxp\Model\Asset\Image;

use OpenDxp\Model\Asset\Thumbnail\ThumbnailInterface as BaseThumbnailInterface;
use OpenDxp\Model\Asset\Thumbnail\ThumbnailMediaInterface;

interface ThumbnailInterface extends BaseThumbnailInterface, ThumbnailMediaInterface
{
    public function getPath(array $args = []): string;

    /**
     * Get generated HTML for displaying the thumbnail image in a HTML document.
     *
     * @param array $options Custom configuration
     */
    public function getHtml(array $options = []): string;

    public function getImageTag(array $options = [], array $removeAttributes = []): string;
}
