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

namespace OpenDxp\Model\DataObject\ClassDefinition\Helper;

use OpenDxp;

/**
 * @internal
 */
abstract class ClassResolver
{
    private static array $cache;

    protected static function resolve(?string $class, callable $validationCallback = null): ?object
    {
        if (!$class) {
            return null;
        }

        return self::$cache[$class] ??= self::returnValidServiceOrNull(
            str_starts_with($class, '@') ? OpenDxp::getContainer()->get(substr($class, 1)) : new $class,
            $validationCallback
        );
    }

    private static function returnValidServiceOrNull(object $service, callable $validationCallback = null): ?object
    {
        if ($validationCallback && !$validationCallback($service)) {
            return null;
        }

        return $service;
    }
}
