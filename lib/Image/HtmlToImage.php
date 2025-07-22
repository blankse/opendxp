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

namespace OpenDxp\Image;

use Exception;
use Gotenberg\Gotenberg as GotenbergAPI;
use HeadlessChromium\BrowserFactory;
use HeadlessChromium\Communication\Connection;
use HeadlessChromium\Communication\Message;
use OpenDxp\Config;
use OpenDxp\Helper\GotenbergHelper;
use OpenDxp\Logger;
use OpenDxp\Tool\Console;
use Throwable;

/**
 * @internal
 */
class HtmlToImage
{
    private static ?string $supportedAdapter = null;

    public static function isSupported(): bool
    {
        return (bool) self::getSupportedAdapter();
    }

    private static function getSupportedAdapter(): string
    {
        if (self::$supportedAdapter !== null) {
            return self::$supportedAdapter;
        }

        self::$supportedAdapter = '';

        if (GotenbergHelper::isAvailable()) {
            /** @var GotenbergAPI|object $chrome */
            $chrome = GotenbergAPI::chromium(Config::getSystemConfiguration('gotenberg')['base_url']);
            if (method_exists($chrome, 'screenshot')) {
                // only v2 of Gotenberg lib is supported
                self::$supportedAdapter = 'gotenberg';
            }
        }

        if (!self::$supportedAdapter && class_exists(BrowserFactory::class)) {
            $chromiumUri = \OpenDxp\Config::getSystemConfiguration('chromium')['uri'];
            if (!empty($chromiumUri)) {
                try {
                    if ((new Connection($chromiumUri))->connect()) {
                        self::$supportedAdapter = 'chromium';
                    }
                } catch (Exception $e) {
                    Logger::debug((string) $e);
                    // nothing to do
                }
            }

            if (self::getChromiumBinary()) {
                self::$supportedAdapter = 'chromium';
            }
        }

        return self::$supportedAdapter;
    }

    public static function getChromiumBinary(): ?string
    {
        foreach (['chromium', 'chrome'] as $app) {
            $chromium = Console::getExecutable($app);
            if ($chromium) {
                return $chromium;
            }
        }

        return null;
    }

    /**
     * @throws Exception
     */
    public static function convert(string $url, string $outputFile, ?string $sessionName = null, ?string $sessionId = null, string $windowSize = '1280,1024'): bool
    {
        $adapter = self::getSupportedAdapter();
        if ($adapter === 'gotenberg') {
            return self::convertGotenberg(...func_get_args());
        } elseif ($adapter === 'chromium') {
            return self::convertChromium(...func_get_args());
        }

        return false;
    }

    public static function convertGotenberg(string $url, string $outputFile, ?string $sessionName = null, ?string $sessionId = null, string $windowSize = '1280,1024'): bool
    {
        try {
            /** @var GotenbergAPI|object $request */
            $request = GotenbergAPI::chromium(Config::getSystemConfiguration('gotenberg')['base_url']);
            if (method_exists($request, 'screenshot')) {
                $sizes = explode(',', $windowSize);
                $urlResponse = $request->screenshot()
                    ->width((int) $sizes[0])
                    ->height((int) $sizes[1])
                    ->png()
                    ->url($url);

                $file = GotenbergAPI::save($urlResponse, OPENDXP_SYSTEM_TEMP_DIRECTORY);

                return rename(OPENDXP_SYSTEM_TEMP_DIRECTORY . '/' . $file, $outputFile);
            }

        } catch (Exception $e) {
            // nothing to do
        }

        return false;
    }

    /**
     * @throws Exception
     */
    public static function convertChromium(string $url, string $outputFile, ?string $sessionName = null, ?string $sessionId = null, string $windowSize = '1280,1024'): bool
    {
        trigger_deprecation('open-dxp/opendxp', '11.2.0', 'Chromium service is deprecated and will be removed in OpenDxp 12. Use Gotenberg instead.');

        $chromiumUri = \OpenDxp\Config::getSystemConfiguration('chromium')['uri'];
        if (!empty($chromiumUri)) {
            try {
                $browser = BrowserFactory::connectToBrowser($chromiumUri);
            } catch (Exception $e) {
                Logger::debug((string) $e);

                return false;
            }
        } else {
            $binary = self::getChromiumBinary();
            if (!$binary) {
                return false;
            }
            $browserFactory = new BrowserFactory($binary);
            $browser = $browserFactory->createBrowser([
                'noSandbox' => file_exists('/.dockerenv'),
                'startupTimeout' => 120,
                'windowSize' => explode(',', $windowSize),
            ]);
        }

        $headers = [];
        if (null !== $sessionId && null !== $sessionName) {
            $headers['Cookie'] = $sessionName . '=' . $sessionId;
        }

        $page = $browser->createPage();

        try {

            if (!empty($headers)) {
                $page->getSession()->sendMessageSync(new Message(
                    'Network.setExtraHTTPHeaders',
                    ['headers' => $headers]
                ));
            }

            $page->navigate($url)->waitForNavigation();

            $page->screenshot([
                'captureBeyondViewport' => true,
                'clip' => $page->getFullPageClip(),
            ])->saveToFile($outputFile);
        } catch (Throwable $e) {
            Logger::debug('Could not create image from url ' . $url . ': ' . $e);

            return false;
        } finally {
            $page->close();
        }

        return true;
    }
}
