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

namespace OpenDxp\Bundle\XliffBundle\ImporterService;

use Exception;
use OpenDxp\Bundle\XliffBundle\AttributeSet\AttributeSet;
use OpenDxp\Bundle\XliffBundle\ImporterService\Importer\ImporterInterface;

class ImporterService implements ImporterServiceInterface
{
    /**
     * @var ImporterInterface[]
     */
    private array $importers = [];

    public function import(AttributeSet $attributeSet, bool $saveElement = true): void
    {
        $this->getImporter($attributeSet->getTranslationItem()->getType())->import($attributeSet, $saveElement);
    }

    public function registerImporter(string $type, ImporterInterface $importer): ImporterServiceInterface
    {
        $this->importers[$type] = $importer;

        return $this;
    }

    public function getImporter(string $type): ImporterInterface
    {
        if (isset($this->importers[$type])) {
            return $this->importers[$type];
        }

        throw new Exception(sprintf('no importer for type "%s" registered', $type));
    }
}
