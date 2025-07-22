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

use OpenDxp\Tests\Support\Util\Autoloader;

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    define('OPENDXP_PROJECT_ROOT', __DIR__ . '/..');
} elseif (file_exists(__DIR__ . '/../../../../vendor/autoload.php')) {
    define('OPENDXP_PROJECT_ROOT', __DIR__ . '/../../../..');
} elseif (getenv('OPENDXP_PROJECT_ROOT')) {
    if (file_exists(getenv('OPENDXP_PROJECT_ROOT') . '/vendor/autoload.php')) {
        define('OPENDXP_PROJECT_ROOT', getenv('OPENDXP_PROJECT_ROOT'));
    } else {
        throw new \Exception('Invalid OpenDxp project root "' . getenv('OPENDXP_PROJECT_ROOT') . '"');
    }
} else {
    throw new \Exception('Unknown configuration! OpenDxp project root not found, please set env variable OPENDXP_PROJECT_ROOT.');
}

include OPENDXP_PROJECT_ROOT . '/vendor/autoload.php';
\OpenDxp\Bootstrap::setProjectRoot();
\OpenDxp\Bootstrap::bootstrap();

Autoloader::addNamespace('OpenDxp\Model\DataObject', __DIR__ . '/_output/var/classes/DataObject');
Autoloader::addNamespace('OpenDxp\Tests', __DIR__);

if (!defined('OPENDXP_TEST')) {
    define('OPENDXP_TEST', true);
}
