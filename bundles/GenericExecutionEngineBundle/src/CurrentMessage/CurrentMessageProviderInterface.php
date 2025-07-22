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

namespace OpenDxp\Bundle\GenericExecutionEngineBundle\CurrentMessage;

/**
 * @internal
 */
interface CurrentMessageProviderInterface
{
    public function getTranslationMessages(
        string $key,
        array $parameters = [],
        string $domain = null
    ): MessageInterface;

    public function getPlainMessage(string $message): MessageInterface;

    /**
     * If string is a valid json translation object it will be converted to TranslationMessage
     * otherwise it will be converted to PlainMessage
     */
    public function getMessageFromSerializedString(string $message): MessageInterface;
}
