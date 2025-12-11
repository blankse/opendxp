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

namespace OpenDxp\Model\DataObject\ClassDefinition\Data\Geo;

use OpenDxp\Model\DataObject\ClassDefinition\Data;
use OpenDxp\Model\DataObject\ClassDefinition\Data\AfterDecryptionUnmarshallerInterface;
use OpenDxp\Model\DataObject\ClassDefinition\Data\BeforeEncryptionMarshallerInterface;
use OpenDxp\Model\DataObject\ClassDefinition\Data\TypeDeclarationSupportInterface;
use OpenDxp\Model\DataObject\Concrete;
use OpenDxp\Model\DataObject\Traits\DataHeightTrait;
use OpenDxp\Model\DataObject\Traits\DataWidthTrait;
use OpenDxp\Tool\Serialize;

abstract class AbstractGeo extends Data implements TypeDeclarationSupportInterface, BeforeEncryptionMarshallerInterface, AfterDecryptionUnmarshallerInterface
{
    use DataHeightTrait;
    use DataWidthTrait;

    /**
     * @internal
     */
    public float $lat = 0.0;

    /**
     * @internal
     */
    public float $lng = 0.0;

    /**
     * @internal
     */
    public int $zoom = 1;

    /**
     * @internal
     */
    public string $mapType = 'roadmap';

    public function getLat(): float
    {
        return $this->lat;
    }

    /**
     * @return $this
     */
    public function setLat(float $lat): static
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): float
    {
        return $this->lng;
    }

    /**
     * @return $this
     */
    public function setLng(float $lng): static
    {
        $this->lng = $lng;

        return $this;
    }

    public function getZoom(): int
    {
        return $this->zoom;
    }

    /**
     * @return $this
     */
    public function setZoom(int $zoom): static
    {
        $this->zoom = $zoom;

        return $this;
    }

    public function marshalBeforeEncryption(mixed $value, ?Concrete $object = null, array $params = []): mixed
    {
        return Serialize::serialize($value);
    }

    public function unmarshalAfterDecryption(mixed $value, ?Concrete $object = null, array $params = []): mixed
    {
        return Serialize::unserialize($value);
    }
}
