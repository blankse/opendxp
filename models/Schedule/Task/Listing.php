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

namespace OpenDxp\Model\Schedule\Task;

use OpenDxp\Model;

/**
 * @internal
 *
 * @method \OpenDxp\Model\Schedule\Task\Listing\Dao getDao()
 * @method Model\Schedule\Task[] load()
 * @method Model\Schedule\Task|false current()
 */
class Listing extends Model\Listing\AbstractListing
{
    /**
     * @return Model\Schedule\Task[]
     */
    public function getTasks(): array
    {
        return $this->getData();
    }

    /**
     * @param Model\Schedule\Task[]|null $tasks
     *
     * @return $this
     */
    public function setTasks(?array $tasks): static
    {
        return $this->setData($tasks);
    }
}
