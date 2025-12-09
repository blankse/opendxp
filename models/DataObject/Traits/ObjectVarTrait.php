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

namespace OpenDxp\Model\DataObject\Traits;

use Exception;
use OpenDxp\Model\AbstractModel;
use OpenDxp\Model\DataObject\OwnerAwareFieldInterface;

/**
 * @internal
 */
trait ObjectVarTrait
{
    /**
     * returns object values without the dao
     */
    public function getObjectVars(): array
    {
        $data = get_object_vars($this);

        if ($this instanceof AbstractModel && isset($data['dao'])) {
            unset($data['dao']);
        }

        if ($this instanceof OwnerAwareFieldInterface && isset($data['_owner'])) {
            unset($data['_owner']);
        }

        return $data;
    }

    public function getObjectVar(?string $var): mixed
    {
        if (!$var || !property_exists($this, $var)) {
            return null;
        }

        return $this->{$var};
    }

    /**
     * @return $this
     *
     * @throws Exception
     */
    public function setObjectVar(string $var, mixed $value, bool $silent = false): static
    {
        if (!property_exists($this, $var)) {
            if ($silent) {
                return $this;
            }

            throw new Exception('property ' . $var . ' does not exist');
        }
        $this->$var = $value;

        return $this;
    }
}
