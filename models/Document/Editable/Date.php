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

namespace OpenDxp\Model\Document\Editable;

use Carbon\Carbon;
use DateTimeInterface;
use OpenDxp\Model;

/**
 * @method \OpenDxp\Model\Document\Editable\Dao getDao()
 */
class Date extends Model\Document\Editable implements EditmodeDataInterface
{
    /**
     * Contains the date
     *
     * @internal
     *
     */
    protected ?\Carbon\Carbon $date = null;

    public function getType(): string
    {
        return 'date';
    }

    public function getData(): mixed
    {
        return $this->date;
    }

    public function getDate(): ?\Carbon\Carbon
    {
        return $this->getData();
    }

    public function getDataEditmode(): ?int
    {
        if ($this->date) {
            return $this->date->getTimestamp();
        }

        return null;
    }

    public function frontend()
    {
        if ($this->date instanceof Carbon) {
            if (isset($this->config['outputIsoFormat']) && $this->config['outputIsoFormat']) {
                return $this->date->isoFormat($this->config['outputIsoFormat']);
            } elseif (isset($this->config['outputFormat']) && $this->config['outputFormat']) {
                trigger_deprecation(
                    'open-dxp/opendxp',
                    '11.2',
                    'Using "outputFormat" config for %s editable is deprecated, use "outputIsoFormat" config instead.',
                    __CLASS__
                );

                return $this->date->isoFormat($this->config['outputFormat']);
            } else {
                if (isset($this->config['format']) && $this->config['format']) {
                    $format = $this->config['format'];
                } else {
                    $format = DateTimeInterface::ATOM;
                }

                return $this->date->format($format);
            }
        }

        return '';
    }

    public function getDataForResource(): mixed
    {
        if ($this->date) {
            return $this->date->getTimestamp();
        }

        return null;
    }

    public function setDataFromResource(mixed $data): static
    {
        if ($data) {
            $this->setDateFromTimestamp((int)$data);
        }

        return $this;
    }

    public function setDataFromEditmode(mixed $data): static
    {
        if (strlen((string) $data) > 5) {
            $timestamp = strtotime($data);
            $this->setDateFromTimestamp($timestamp);
        }

        return $this;
    }

    public function isEmpty(): bool
    {
        if ($this->date) {
            return false;
        }

        return true;
    }

    private function setDateFromTimestamp(int $timestamp): void
    {
        $this->date = new Carbon();
        $this->date->setTimestamp($timestamp);
    }
}
