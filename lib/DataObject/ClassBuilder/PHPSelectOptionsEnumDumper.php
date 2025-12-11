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

namespace OpenDxp\DataObject\ClassBuilder;

use OpenDxp\Model\DataObject\SelectOptions\Config;
use Symfony\Component\Filesystem\Filesystem;

class PHPSelectOptionsEnumDumper implements PHPSelectOptionsEnumDumperInterface
{
    public function __construct(
        protected SelectOptionsEnumBuilderInterface $enumBuilder,
        protected Filesystem $filesystem,
    ) {
    }

    public function dumpPHPEnum(Config $config): void
    {
        $filePath = $config->getPhpClassFile();
        $enum = $this->enumBuilder->buildEnum($config);

        $this->filesystem->dumpFile($filePath, $enum);
    }
}
