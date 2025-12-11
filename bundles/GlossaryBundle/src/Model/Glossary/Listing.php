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

namespace OpenDxp\Bundle\GlossaryBundle\Model\Glossary;

use OpenDxp\Bundle\GlossaryBundle\Model\Glossary;
use OpenDxp\Model\Listing\AbstractListing;

/**
 * @method Listing\Dao getDao()
 * @method Glossary[] load()
 * @method Glossary|false current()
 * @method int getTotalCount()
 * @method list<array<string,mixed>> getDataArray()
 */
class Listing extends AbstractListing
{
    /**
     * @return Glossary[]
     */
    public function getGlossary(): array
    {
        return $this->getData();
    }

    /**
     * @param Glossary[]|null $glossary
     *
     * @return $this
     */
    public function setGlossary(?array $glossary): static
    {
        return $this->setData($glossary);
    }
}
