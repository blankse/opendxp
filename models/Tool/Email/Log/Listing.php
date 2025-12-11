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

namespace OpenDxp\Model\Tool\Email\Log;

use OpenDxp\Model;

/**
 * @internal
 *
 * @method \OpenDxp\Model\Tool\Email\Log\Listing\Dao getDao()
 * @method Model\Tool\Email\Log[] load()
 * @method Model\Tool\Email\Log|false current()
 * @method int getTotalCount()
 */
class Listing extends Model\Listing\AbstractListing
{
    /**
     * @return Model\Tool\Email\Log[]
     */
    public function getEmailLogs(): array
    {
        return $this->getData();
    }

    /**
     * Sets EmailLog entries
     *
     *
     * @return $this
     */
    public function setEmailLogs(array $emailLogs): static
    {
        return $this->setData($emailLogs);
    }
}
