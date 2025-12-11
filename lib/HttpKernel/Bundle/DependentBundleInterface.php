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

namespace OpenDxp\HttpKernel\Bundle;

use OpenDxp\HttpKernel\BundleCollection\BundleCollection;

/**
 * Defines a bundle which has dependencies on other bundles. When adding a DependentBundle to the collection, the
 * collection will call the static method to register additional bundles.
 */
interface DependentBundleInterface
{
    /**
     * Register bundles to collection.
     *
     * WARNING: this method will be called as soon as this bundle is added to the collection, independent if
     * it will finally be included due to environment restrictions. If you need to load your dependencies conditionally,
     * specify the environments to use on the collection item.
     */
    public static function registerDependentBundles(BundleCollection $collection): void;
}
