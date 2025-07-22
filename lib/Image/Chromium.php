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

namespace OpenDxp\Image;

trigger_deprecation('open-dxp/opendxp', '11.2', 'The "%s" class is deprecated, use "%s" instead.', Chromium::class, HtmlToImage::class);

if (!class_exists(Chromium::class, false)) {
    class_alias(HtmlToImage::class, Chromium::class);
}

if (false) {
    /**
     * @deprecated since OpenDXP 11.2, use HtmlToImage instead
     */
    class Chromium extends HtmlToImage
    {
    }
}
