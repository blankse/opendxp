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

namespace OpenDxp\Event\Model\Asset;

use OpenDxp\Event\Traits\ArgumentsAwareTrait;
use Symfony\Contracts\EventDispatcher\Event;

class ResolveUploadTargetEvent extends Event
{
    use ArgumentsAwareTrait;

    protected string $filename;

    /**
     * @deprecated Will be removed in OpenDxp 12
     */
    protected array $context = [];

    protected int $parentId;

    /**
     * ResolveUploadTargetEvent constructor.
     *
     * @param array|null $context contextual information
     */
    public function __construct(int $parentId, string $filename, ?array $context = null)
    {
        $this->parentId = $parentId;
        $this->filename = $filename;
        if ($context !== null) {
            trigger_deprecation(
                'open-dxp/opendxp',
                '11.5.0',
                'The context property is deprecated and will be removed in 12.0.0.
            Use setArgument() from the ArgumentsAwareTrait instead.'
            );

            $this->context = $context;
        }
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }

    /**
     * @deprecated Will be removed in OpenDxp 12
     */
    public function getContext(): array
    {
        trigger_deprecation(
            'open-dxp/opendxp',
            '11.5.0',
            'The context property is deprecated and will be removed in 12.0.0.
            Use getArgument() from the ArgumentsAwareTrait instead.'
        );

        return $this->context;
    }

    /**
     * @deprecated Will be removed in OpenDxp 12
     */
    public function setContext(array $context): void
    {
        trigger_deprecation(
            'open-dxp/opendxp',
            '11.5.0',
            'The context property is deprecated and will be removed in 12.0.0.
            Use setArgument() from the ArgumentsAwareTrait instead.'
        );

        $this->context = $context;
    }

    public function getParentId(): int
    {
        return $this->parentId;
    }

    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * Will be removed in OpenDxp 12
     *
     * Override setArgument to handle the deprecated context property.
     */
    public function setArgument(string $key, mixed $value): static
    {
        if ($key === 'context' && is_array($value)) {
            $this->context = $value;
        }

        $this->arguments[$key] = $value;

        return $this;
    }
}
