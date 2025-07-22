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

use OpenDxp;
use OpenDxp\Templating\TwigDefaultDelegatingEngine;
use OpenDxp\Tests\Support\Test\TestCase;
use Twig\Loader\ArrayLoader;

class OpenDxpPlaceholderTest extends TestCase
{
    private TwigDefaultDelegatingEngine $engine;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var TwigDefaultDelegatingEngine $templatingEngine */
        $templatingEngine = OpenDxp::getContainer()->get('opendxp.templating.engine.delegating');

        $this->engine = $templatingEngine;
    }

    public function testBasic(): void
    {
        $this->engine->getTwigEnvironment()->setLoader(new ArrayLoader([
            'twig' => <<<TWIG
                {% do opendxp_placeholder('foo').set("Some text for later") %}
                <h3>First copy:</h3>
                {{ opendxp_placeholder('foo') }}
                <br/>
                <hr/>
                <h3>Second copy:</h3>
                {{ opendxp_placeholder('foo') }}
                <br/>
                <hr/>
                <h3>Third copy:</h3>
                {{ opendxp_placeholder('foo') }}
                <br/>
            TWIG,
        ]));

        $result = $this->engine->render('twig');

        $this->assertStringContainsString(<<<TEXT
                <h3>First copy:</h3>
                Some text for later
                <br/>
                <hr/>
                <h3>Second copy:</h3>
                Some text for later
                <br/>
                <hr/>
                <h3>Third copy:</h3>
                Some text for later
                <br/>
            TEXT,
            $result,
        );
    }

    public function testAggregateContent(): void
    {
        $this->engine->getTwigEnvironment()->setLoader(new ArrayLoader([
            'twig' => <<<TWIG
            {% do opendxp_placeholder('foo').setPrefix("<ul>\n<li>")
                .setSeparator("</li>\n<li>")
                .setIndent(4)
                .setPostfix("</li>\n</ul>")
            %}
            {% for datum in data %}{% do opendxp_placeholder('foo').append(datum.title) %}{% endfor %}
            <h3>First copy:</h3>
            {{ opendxp_placeholder('foo') }}
            <br/>
            <hr/>
            <h3>Second copy:</h3>
            {{ opendxp_placeholder('foo') }}
            <br/>
            <hr/>
            <h3>Third copy:</h3>
            {{ opendxp_placeholder('foo') }}
            <br/>
            TWIG,
        ]));

        $result = $this->engine->render(
            'twig',
            [
                'data' => [
                    ['title' => 'oh'],
                    ['title' => 'my'],
                    ['title' => 'list'],
                ],
            ],
        );

        $this->assertStringContainsString(<<<TEXT
            <h3>First copy:</h3>
                <ul>
                <li>oh</li>
                <li>my</li>
                <li>list</li>
                </ul>
            <br/>
            <hr/>
            <h3>Second copy:</h3>
                <ul>
                <li>oh</li>
                <li>my</li>
                <li>list</li>
                </ul>
            <br/>
            <hr/>
            <h3>Third copy:</h3>
                <ul>
                <li>oh</li>
                <li>my</li>
                <li>list</li>
                </ul>
            <br/>
            TEXT,
            $result,
        );
    }

    public function testCaptureContent(): void
    {
        $this->engine->getTwigEnvironment()->setLoader(new ArrayLoader([
            'twig' => <<<TWIG
            {% do opendxp_placeholder('foo').captureStart() %}
            {% for datum in data %}
            <div class="foo">
                <h2>{{ datum.title }}</h2>
                <p>{{ datum.content }}</p>
            </div>
            {% endfor %}
            {% do opendxp_placeholder('foo').captureEnd() %}
            <h3>First copy:</h3>
            {{ opendxp_placeholder('foo') }}
            <br/>
            <hr/>
            <h3>Second copy:</h3>
            {{ opendxp_placeholder('foo') }}
            <br/>
            <hr/>
            <h3>Third copy:</h3>
            {{ opendxp_placeholder('foo') }}
            <br/>
            TWIG,
        ]));

        $result = $this->engine->render(
            'twig',
            [
                'data' => [
                    [
                        'title' => 'Title 1',
                        'content' => 'Content 1',
                    ],
                    [
                        'title' => 'Title 2',
                        'content' => 'Content 2',
                    ],
                ],
            ],
        );

        $this->assertStringContainsString(<<<TEXT
            <h3>First copy:</h3>
            <div class="foo">
                <h2>Title 1</h2>
                <p>Content 1</p>
            </div>
            <div class="foo">
                <h2>Title 2</h2>
                <p>Content 2</p>
            </div>

            <br/>
            <hr/>
            <h3>Second copy:</h3>
            <div class="foo">
                <h2>Title 1</h2>
                <p>Content 1</p>
            </div>
            <div class="foo">
                <h2>Title 2</h2>
                <p>Content 2</p>
            </div>

            <br/>
            <hr/>
            <h3>Third copy:</h3>
            <div class="foo">
                <h2>Title 1</h2>
                <p>Content 1</p>
            </div>
            <div class="foo">
                <h2>Title 2</h2>
                <p>Content 2</p>
            </div>

            <br/>
            TEXT,
            $result,
        );
    }

    public function testIssue16973(): void
    {
        $this->engine->getTwigEnvironment()->setLoader(new ArrayLoader([
            'twig' => <<<TWIG
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>Example</title>
            </head>

            <body>
            {# Default capture: append #}
            {% set data = [{"title": "title1", "content": "content1"}, {"title": "title2", "content": "content2"}] %}

            {% do opendxp_placeholder('foo').captureStart() %}

            {# If placeholder is working this section is not rendered directly but captured into placeholder#}

            {% for datum in data %}
                <div class="foo">
                    <h2>{{ datum.title }}</h2>
                    <p>{{ datum.content }}</p>
                </div>
            {% endfor %}

            {% do opendxp_placeholder('foo').captureEnd() %}

            {# If placeholder is working it should render three sections of same content #}

            <h3>First copy:</h3>
            {{ opendxp_placeholder('foo') }}
            <br/>
            <hr/>
            <h3>Second copy:</h3>
            {{ opendxp_placeholder('foo') }}
            <br/>
            <hr/>
            <h3>Third copy:</h3>
            {{ opendxp_placeholder('foo') }}
            <br/>
            <hr/>
            </body>
            </html>
            TWIG,
        ]));
        $result = $this->engine->render('twig');

        $this->assertStringContainsString(<<<TEXT
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>Example</title>
            </head>

            <body>



            <h3>First copy:</h3>


                <div class="foo">
                    <h2>title1</h2>
                    <p>content1</p>
                </div>
                <div class="foo">
                    <h2>title2</h2>
                    <p>content2</p>
                </div>


            <br/>
            <hr/>
            <h3>Second copy:</h3>


                <div class="foo">
                    <h2>title1</h2>
                    <p>content1</p>
                </div>
                <div class="foo">
                    <h2>title2</h2>
                    <p>content2</p>
                </div>


            <br/>
            <hr/>
            <h3>Third copy:</h3>


                <div class="foo">
                    <h2>title1</h2>
                    <p>content1</p>
                </div>
                <div class="foo">
                    <h2>title2</h2>
                    <p>content2</p>
                </div>


            <br/>
            <hr/>
            </body>
            </html>
            TEXT,
            $result,
        );
    }
}
