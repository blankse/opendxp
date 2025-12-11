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

namespace OpenDxp\Model;

use Exception;
use OpenDxp\Model\Dao\DaoInterface;

interface ModelInterface
{
    public function getDao(): DaoInterface;

    public function setDao(Dao\AbstractDao $dao): static;

    /**
     * @throws Exception
     */
    public function initDao(?string $key = null, bool $forceDetection = false): void;

    public function setValues(array $data = []): static;

    public function setValue(string $key, mixed $value, bool $ignoreEmptyValues = false): static;
}
