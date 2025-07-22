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

namespace OpenDxp\Migrations;

use Doctrine\Migrations\Configuration\Configuration;
use Doctrine\Migrations\Version\Comparator;
use Doctrine\Migrations\Version\Version;
use ErrorException;
use ReflectionClass;
use ReflectionException;

/**
 * Orders versions by their namespaces in registration order.
 */
final class DirectoryAwareVersionComparator implements Comparator
{
    /** @var array<string, int> */
    private array $orderByNamespace;

    public function __construct(Configuration $config)
    {
        $this->orderByNamespace = array_flip(array_keys($config->getMigrationDirectories()));
    }

    public function compare(Version $a, Version $b): int
    {
        try {
            return $this->getOrder($a) <=> $this->getOrder($b);
        } catch (ReflectionException|ErrorException) {
            return (string) $a <=> (string) $b;
        }
    }

    /**
     * @throws ReflectionException
     * @throws ErrorException if the migration file is not found
     *
     * @return array{int, string}
     */
    private function getOrder(Version $version): array
    {
        $class = new ReflectionClass((string) $version);
        $className = $class->getShortName();
        $namespace = $class->getNamespaceName();
        $namespaceOrder = $this->orderByNamespace[$namespace] ?? 0;

        return [$namespaceOrder, $className];
    }
}
