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

namespace OpenDxp\Tests\Twig;

use Carbon\Carbon;
use OpenDxp;
use OpenDxp\Tests\Support\Test\TestCase;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class OpenDxpDateTest extends TestCase
{
    private Environment $twig;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var Environment $twig */
        $twig = OpenDxp::getContainer()->get('opendxp.templating');

        $this->twig = $twig;
    }

    public function testOpenDxpDateOutputFormat(): void
    {
        $backupLocale = setlocale(LC_TIME, '0');
        setlocale(LC_TIME, 'en_US.UTF-8');

        $this->twig->setLoader(new ArrayLoader([
            'twig' => <<<TWIG
            {{ opendxp_date("myDate", {
                "format": "d.m.Y",
                "outputIsoFormat": "dddd, MMMM D, YYYY h:mm"
            }) }}
            TWIG,
        ]));
        $snippet = new OpenDxp\Model\Document\Snippet();
        $date = (new OpenDxp\Model\Document\Editable\Date())
            ->setName('myDate')
            ->setDataFromResource(1733954969)
        ;
        $snippet->setEditable($date);

        $result = $this->twig->render(
            'twig',
            [
                'document' => $snippet,
            ]
        );

        $this->assertEquals('Wednesday, December 11, 2024 10:09', $result);

        setlocale(LC_TIME, $backupLocale);
    }

    public function testOpenDxpDateOutputIsoFormat(): void
    {
        $backupCarbonLocale = Carbon::getLocale();
        Carbon::setLocale('de_DE.utf8');

        $this->twig->setLoader(new ArrayLoader([
            'twig' => <<<TWIG
            {{ opendxp_date("myDate", {
                "format": "d.m.Y",
                "outputIsoFormat": "dddd, MMMM D, YYYY h:mm"
            }) }}
            TWIG,
        ]));
        $snippet = new OpenDxp\Model\Document\Snippet();
        $date = (new OpenDxp\Model\Document\Editable\Date())
            ->setName('myDate')
            ->setDataFromResource(1733954969)
        ;
        $snippet->setEditable($date);

        $result = $this->twig->render(
            'twig',
            [
                'document' => $snippet,
            ]
        );

        $this->assertEquals('Mittwoch, Dezember 11, 2024 10:09', $result);

        Carbon::setLocale($backupCarbonLocale);
    }
}
