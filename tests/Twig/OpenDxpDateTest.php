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
use OpenDxp\Templating\TwigDefaultDelegatingEngine;
use OpenDxp\Tests\Support\Test\TestCase;
use Twig\Loader\ArrayLoader;

class OpenDxpDateTest extends TestCase
{
    private TwigDefaultDelegatingEngine $engine;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var TwigDefaultDelegatingEngine $templatingEngine */
        $templatingEngine = OpenDxp::getContainer()->get('opendxp.templating.engine.delegating');

        $this->engine = $templatingEngine;
    }

    public function testOpenDxpDateOutputFormat(): void
    {
        $backupLocale = setlocale(LC_TIME, '0');
        setlocale(LC_TIME, 'en_US.UTF-8');

        $this->engine->getTwigEnvironment()->setLoader(new ArrayLoader([
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

        $result = $this->engine->render(
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

        $this->engine->getTwigEnvironment()->setLoader(new ArrayLoader([
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

        $result = $this->engine->render(
            'twig',
            [
                'document' => $snippet,
            ]
        );

        $this->assertEquals('Mittwoch, Dezember 11, 2024 10:09', $result);

        Carbon::setLocale($backupCarbonLocale);
    }
}
