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

namespace OpenDxp\Model\Element\WorkflowState;

use OpenDxp\Model;

/**
 * @method \OpenDxp\Model\Element\WorkflowState\Listing\Dao getDao()
 * @method Model\Element\WorkflowState[] load()
 * @method Model\Element\WorkflowState|false current()
 */
class Listing extends Model\Listing\AbstractListing
{
    /**
     * @param Model\Element\WorkflowState[]|null $workflowStates
     *
     * @return $this
     */
    public function setWorkflowStates(?array $workflowStates): static
    {
        return $this->setData($workflowStates);
    }

    /**
     * @return Model\Element\WorkflowState[]
     */
    public function getWorkflowStates(): array
    {
        return $this->getData();
    }
}
