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

namespace OpenDxp\Workflow;

use DateTime;
use OpenDxp\Logger;
use OpenDxp\Model\Element;
use OpenDxp\Model\User;

class Service
{
    /**
     * @param array $fc - The field configuration from the Workflow
     * @param mixed $value - The value
     *
     */
    public static function createNoteData(array $fc, mixed $value): array
    {
        $data = [];

        //supported types for notes are text, date, document, asset, object, bool
        if ($fc['fieldType'] === 'checkbox') {
            $data['type'] = 'bool';
            $data['value'] = (bool) $value;
        } elseif (in_array($fc['fieldType'], ['date', 'datetime'])) {
            $data['type'] = 'date';

            $dateTime = new DateTime();

            if (empty($fc['timeformat']) || $fc['timeformat'] === 'milliseconds') {
                $dateTime->setTimestamp($value / 1000);
            } else {
                $dateTime->setTimestamp($value);
            }
            $data['value'] = $dateTime;
            /**
            } elseif (false) { //TODO

                $data['type'] = 'document';
                $data['value'] = $value;
            } elseif (false) { //TODO

                $data['type'] = 'asset';
                $data['value'] = $value;
            } elseif (false) { //TODO

                $data['type'] = 'object';
                $data['value'] = $value;
            */
        } else {
            $data['type'] = 'text';
            $data['value'] = $value;
        }

        $data['key'] = $fc['name'];

        return $data;
    }

    public static function getDataFromEditmode(mixed $data, string $openDxpTagName): mixed
    {
        $tagClass = '\\OpenDxp\\Model\\DataObject\\ClassDefinition\\Data\\' . ucfirst($openDxpTagName);
        if (\OpenDxp\Tool::classExists($tagClass)) {
            /**
             * @var \OpenDxp\Model\DataObject\ClassDefinition\Data $tag
             */
            $tag = new $tagClass();

            return $tag->getDataFromEditmode($data);
        }

        //purposely return null if there is no valid class, log a warning
        Logger::warning("No valid opendxp tag found for fieldType ({$openDxpTagName}), check 'fieldType' exists, and 'type' is not being used in config");

        return null;
    }

    /**
     * Creates a note for an action with a transition
     *
     *
     * @return Element\Note $note
     */
    public static function createActionNote(Element\ElementInterface $element, string $type, string $title, string $description, array $noteData, ?User $user = null): Element\Note
    {
        //prepare some vars for creating the note
        if (!$user) {
            $user = \OpenDxp\Tool\Admin::getCurrentUser();
        }

        $note = new Element\Note();
        $note->setElement($element);
        $note->setDate(time());
        $note->setType($type);
        $note->setTitle($title);
        $note->setDescription($description);
        $note->setUser($user ? $user->getId() : 0);

        foreach ($noteData as $row) {
            if ($row['key'] === 'noteDate' && $row['type'] === 'date') {
                /**
                 * @var DateTime $date
                 */
                $date = $row['value'];
                $note->setDate($date->getTimestamp());
            } else {
                $note->addData($row['key'], $row['type'], $row['value']);
            }
        }

        $note->save();

        return $note;
    }
}
