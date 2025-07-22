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

namespace OpenDxp\Bundle\GenericExecutionEngineBundle\Messenger\Messages;

use OpenDxp\Model\Element\ElementDescriptor;

abstract class AbstractExecutionEngineMessage implements GenericExecutionEngineMessageInterface
{
    /**
     * @param ElementDescriptor[] $elements
     */
    public function __construct(
        protected int $jobRunId,
        protected int $currentJobStep,
        protected ?ElementDescriptor $element = null,
        protected array $elements = []
    ) {
    }

    public function getJobRunId(): int
    {
        return $this->jobRunId;
    }

    public function getCurrentJobStep(): int
    {
        return $this->currentJobStep;
    }

    public function getElement(): ?ElementDescriptor
    {
        return $this->element;
    }
}
