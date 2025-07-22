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

namespace OpenDxp\Model\Element\Note;

use OpenDxp\Model;

/**
 * @method Model\Element\Note\Listing\Dao getDao()
 * @method Model\Element\Note[] load()
 * @method Model\Element\Note|false current()
 * @method int[] loadIdList()
 * @method int getTotalCount()
 */
class Listing extends Model\Listing\AbstractListing
{
    /**
     * @param Model\Element\Note[]|null $notes
     *
     * @return $this
     */
    public function setNotes(?array $notes): static
    {
        return $this->setData($notes);
    }

    /**
     * @return Model\Element\Note[]
     */
    public function getNotes(): array
    {
        return $this->getData();
    }
}
