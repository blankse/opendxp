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

namespace OpenDxp\Request\Attribute;

use Attribute;

/**
 * Argument to resolve a DataObject.
 */
#[Attribute(Attribute::TARGET_PARAMETER)]
class DataObjectParam
{
    public function __construct(
        public ?string $class = null,
        public ?bool $unpublished = null,
        public ?array $options = null
    ) {
    }
}
