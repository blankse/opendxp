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

namespace OpenDxp\Extension\Document\Areabrick;

use OpenDxp\Extension\Document\Areabrick\Exception\ConfigurationException;
use OpenDxp\Model\Document\Editable;
use OpenDxp\Model\Document\Editable\Area\Info;
use OpenDxp\Model\Document\PageSnippet;
use OpenDxp\Templating\Renderer\EditableRenderer;

abstract class AbstractAreabrick implements AreabrickInterface, TemplateAreabrickInterface
{
    protected EditableRenderer $editableRenderer;

    /**
     * Called in AreabrickPass
     */
    public function setEditableRenderer(EditableRenderer $editableRenderer): void
    {
        $this->editableRenderer = $editableRenderer;
    }

    protected ?string $id = null;

    public function setId(string $id): void
    {
        // make sure ID is only set once
        if (null !== $this->id) {
            throw new ConfigurationException(sprintf(
                'Brick ID is immutable (trying to set ID %s for brick %s)',
                $id,
                $this->id
            ));
        }

        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->id ? ucfirst($this->id) : '';
    }

    public function getDescription(): string
    {
        return '';
    }

    public function getVersion(): string
    {
        return '';
    }

    public function getIcon(): ?string
    {
        return null;
    }

    public function hasTemplate(): bool
    {
        return true;
    }

    public function action(Info $info): ?\Symfony\Component\HttpFoundation\Response
    {
        // noop - implement as needed
        return null;
    }

    public function postRenderAction(Info $info): ?\Symfony\Component\HttpFoundation\Response
    {
        // noop - implement as needed
        return null;
    }

    public function getHtmlTagOpen(Info $info): string
    {
        return '<div class="opendxp_area_' . $info->getId() . ' opendxp_area_content '. $this->getOpenTagCssClass($info) .'">';
    }

    protected function getOpenTagCssClass(Info $info): ?string
    {
        return null;
    }

    public function getHtmlTagClose(Info $info): string
    {
        return '</div>';
    }

    protected function getDocumentEditable(PageSnippet $document, string $type, string $inputName, array $options = []): Editable\EditableInterface
    {
        return $this->editableRenderer->getEditable($document, $type, $inputName, $options);
    }

    public function needsReload(): bool
    {
        return false;
    }
}
