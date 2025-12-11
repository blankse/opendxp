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

namespace OpenDxp\Bundle\SimpleBackendSearchBundle\Model\Search\Backend\Data;

use OpenDxp\Model\Element;

/**
 * @internal
 */
class Id
{
    protected int $id;

    protected string $type;

    public function __construct(Element\ElementInterface $webResource)
    {
        $this->id = $webResource->getId();
        $this->type = Element\Service::getElementType($webResource) ?: 'unknown';
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
