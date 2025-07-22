<?php

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

namespace OpenDxp\Model\Dao;

use OpenDxp\Model\AbstractModel;

/**
 * @internal
 */
trait DaoTrait
{
    /**
     * @var \OpenDxp\Model\AbstractModel
     */
    protected $model;

    public function setModel(AbstractModel $model): static
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function assignVariablesToModel(array $data): void
    {
        $this->model->setValues($data, true);
    }
}
