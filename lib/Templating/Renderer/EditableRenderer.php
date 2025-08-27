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

namespace OpenDxp\Templating\Renderer;

use Exception;
use OpenDxp\Document\Editable\EditmodeEditableDefinitionCollector;
use OpenDxp\Http\Request\Resolver\EditmodeResolver;
use OpenDxp\Model\Document\Editable;
use OpenDxp\Model\Document\Editable\LazyLoadingInterface;
use OpenDxp\Model\Document\Editable\Loader\EditableLoaderInterface;
use OpenDxp\Model\Document\PageSnippet;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * @internal
 */
class EditableRenderer implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct(
        protected EditableLoaderInterface $editableLoader,
        protected EditmodeResolver $editmodeResolver,
        protected EditmodeEditableDefinitionCollector $configCollector)
    {
    }

    public function editableExists(string $type): bool
    {
        return $this->editableLoader->supports($type);
    }

    /**
     * @throws Exception
     */
    public function getEditable(PageSnippet $document, string $type, string $name, array $config = [], ?bool $editmode = null): Editable\EditableInterface
    {
        $type = strtolower($type);

        $originalName = $name;
        $name = Editable::buildEditableName($type, $originalName, $document);
        $realName = Editable::buildEditableRealName($originalName, $document);

        if (null === $editmode) {
            $editmode = $this->editmodeResolver->isEditmode();
        }

        $editable = $document->getEditable($name);
        if ($editable instanceof Editable\EditableInterface && $editable->getType() === $type) {
            // call the load() method if it exists to reinitialize the data (eg. from serializing, ...)
            if ($editable instanceof LazyLoadingInterface) {
                $editable->load();
            }
        } else {
            $editable = $this->editableLoader->build($type);
            $editable->setName($name);
            $document->setEditable($editable);

            //set default value on initial build
            if (isset($config['defaultValue'])) {
                $editable->setDataFromResource($config['defaultValue']);
            }
        }

        $editable->setDocument($document);
        $editable->setEditmode($editmode);
        // set the real name of this editable, without the prefixes and suffixes from blocks and areablocks
        $editable->setRealName($realName);
        $editable->setConfig($config);

        if ($editmode) {
            $editable->setEditableDefinitionCollector($this->configCollector);
        }

        return $editable;
    }

    /**
     * Renders an editable
     *
     * @throws Exception
     */
    public function render(PageSnippet $document, string $type, string $name, array $options = [], ?bool $editmode = null): Editable\EditableInterface
    {
        return $this->getEditable($document, $type, $name, $options, $editmode);
    }
}
