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

namespace OpenDxp\Model\User;

use OpenDxp\Model\User;

/**
 * @method \OpenDxp\Model\User\Listing\Dao getDao()
 * @method User[] load()
 */
class Listing extends Listing\AbstractListing
{
    protected string $type = 'user';

    /**
     * Alias for $this->getItems()
     *
     * @return \OpenDxp\Model\User[]
     */
    public function getUsers(): array
    {
        return $this->getItems();
    }
}
