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

namespace OpenDxp\Tests\Model\Tool;

use OpenDxp\Cache\RuntimeCache;
use OpenDxp\Model\Document;
use OpenDxp\Model\Site;
use OpenDxp\Tests\Support\Test\ModelTestCase;
use OpenDxp\Tool\Text;

class TextTest extends ModelTestCase
{
    private Document\Page $testingDocument;

    protected function setUp(): void
    {
        parent::setUp();

        $site1 = $this->createSite('site', 'example.com');
        $site2 = $this->createSite('site2', 'example2.com');
        $this->testingDocument = $this->createDocument('testing', $site2->getRootDocument()->getId());
    }

    protected function needsDb(): bool
    {
        return true;
    }

    public function testWysiwygText(): void
    {
        RuntimeCache::clear();

        $text = sprintf(
            'Link to a document <a href="%s" opendxp_id="%s" opendxp_type="document">The link</a>',
            $this->testingDocument->getFullPath(),
            $this->testingDocument->getId()
        );
        $expected = sprintf(
            'Link to a document <a href="http://example2.com/testing" opendxp_id="%s" opendxp_type="document">The link</a>',
            $this->testingDocument->getId()
        );

        $this->assertEquals($expected, Text::wysiwygText($text));
    }

    private function createDocument(string $key, int $parentId): Document\Page
    {
        $document = new Document\Page();
        $document->setKey($key);
        $document->setPublished(true);
        $document->setParentId($parentId);
        $document->setUserOwner(1);
        $document->setUserModification(1);
        $document->setCreationDate(time());
        $document->save();

        return $document;
    }

    private function createSite(string $key, string $mainDomain): Site
    {
        $site = new Site();
        $site->setRootDocument($this->createDocument($key, 1));
        $site->setMainDomain($mainDomain);
        $site->setRootPath('/');
        $site->save();

        return $site;
    }
}
