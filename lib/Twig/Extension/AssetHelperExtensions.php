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
use Twig\Extension\AbstractExtension;
use Twig\TwigTest;

/**
 * @internal
 */
class AssetHelperExtensions extends AbstractExtension
{
    public function getTests(): array
    {
        return [
            new TwigTest('opendxp_asset', static function ($object) {
                return $object instanceof Asset;
            }),
            new TwigTest('opendxp_asset_archive', static function ($object) {
                return $object instanceof Asset\Archive;
            }),
            new TwigTest('opendxp_asset_audio', static function ($object) {
                return $object instanceof Asset\Audio;
            }),
            new TwigTest('opendxp_asset_document', static function ($object) {
                return $object instanceof Asset\Document;
            }),
            new TwigTest('opendxp_asset_folder', static function ($object) {
                return $object instanceof Asset\Folder;
            }),
            new TwigTest('opendxp_asset_image', static function ($object) {
                return $object instanceof Asset\Image;
            }),
            new TwigTest('opendxp_asset_text', static function ($object) {
                return $object instanceof Asset\Text;
            }),
            new TwigTest('opendxp_asset_unknown', static function ($object) {
                return $object instanceof Asset\Unknown;
            }),
            new TwigTest('opendxp_asset_video', static function ($object) {
                return $object instanceof Asset\Video;
            }),
        ];
    }
}
