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

namespace OpenDxp\Model\DataObject\Traits;

use Exception;
use OpenDxp\Model\DataObject\Concrete;
use OpenDxp\Model\DataObject\Fieldcollection\Data\AbstractData;
use OpenDxp\Model\DataObject\Localizedfield;

/**
 * @internal
 */
trait ContextPersistenceTrait
{
    protected function prepareMyCurrentRelations(
        Localizedfield|\OpenDxp\Model\DataObject\Fieldcollection\Data\AbstractData|\OpenDxp\Model\DataObject\Objectbrick\Data\AbstractData|Concrete $object,
        array $params
    ): array {
        if ($object instanceof Concrete) {
            $relations = $object->retrieveRelationData(['fieldname' => $this->getName(), 'ownertype' => 'object']);
        } elseif ($object instanceof AbstractData) {
            $relations = $object->getObject()->retrieveRelationData(
                [
                    'fieldname' => $this->getName(),
                    'ownertype' => 'fieldcollection',
                    'ownername' => $object->getFieldname(),
                    'position' => (string)$object->getIndex(), //Gets cast to string for checking a delta of the relations on removal or addition
                ]
            );
        } elseif ($object instanceof Localizedfield) {
            $context = $params['context'] ?? null;
            if (isset($context['containerType']) &&
                ($context['containerType'] === 'fieldcollection' || $context['containerType'] === 'objectbrick')) {
                $fieldname = $context['fieldname'] ?? null;
                if ($context['containerType'] === 'fieldcollection') {
                    $index = $context['index'] ?? null;
                    $filter = '/'.$context['containerType'].'~'.$fieldname.'/'.$index.'/%';
                } else {
                    $filter = '/'.$context['containerType'].'~'.$fieldname.'/%';
                }
                $relations = $object->getObject()->retrieveRelationData(
                    [
                        'fieldname' => $this->getName(),
                        'ownertype' => 'localizedfield',
                        'ownername' => $filter,
                        'position' => $params['language'],
                    ]
                );
            } else {
                $relations = $object->getObject()->retrieveRelationData(
                    [
                        'fieldname' => $this->getName(),
                        'ownertype' => 'localizedfield',
                        'position' => $params['language'],
                    ]
                );
            }
        } elseif ($object instanceof \OpenDxp\Model\DataObject\Objectbrick\Data\AbstractData) {
            $relations = $object->getObject()->retrieveRelationData(
                [
                    'fieldname' => $this->getName(),
                    'ownertype' => 'objectbrick',
                    'ownername' => $object->getFieldname(),
                    'position' => $object->getType(),
                ]
            );
        } else {
            throw new Exception('Invalid object type');
        }

        return $relations;
    }

    /**
     * Enrich relation / slug with type-specific data.
     */
    protected function enrichDataRow(Localizedfield|AbstractData|\OpenDxp\Model\DataObject\Objectbrick\Data\AbstractData|Concrete $object, array $params, ?string &$classId, array &$row = [], string $srcCol = 'src_id'): void
    {
        if (!$row) {
            $row = [];
        }

        if ($object instanceof Concrete) {
            $row[$srcCol] = $object->getId();
            $row['ownertype'] = 'object';

            $classId = $object->getClassId();
        } elseif ($object instanceof AbstractData) {
            $row[$srcCol] = $object->getObject()->getId(); // use the id from the object, not from the field collection
            $row['ownertype'] = 'fieldcollection';
            $row['ownername'] = $object->getFieldname();
            $row['position'] = (string)$object->getIndex();

            $classId = $object->getObject()->getClassId();
        } elseif ($object instanceof Localizedfield) {
            $row[$srcCol] = $object->getObject()->getId();
            $row['ownertype'] = 'localizedfield';
            $row['ownername'] = 'localizedfield';
            $context = $object->getContext();
            if (isset($context['containerType']) && ($context['containerType'] === 'fieldcollection' || $context['containerType'] === 'objectbrick')) {
                $fieldname = $context['fieldname'];
                $index = $context['index'] ?? $context['containerKey'] ?? null;
                $row['ownername'] = '/' . $context['containerType'] . '~' . $fieldname . '/' . $index . '/localizedfield~' . $row['ownername'];
            }

            $row['position'] = $params['language'];

            $classId = $object->getObject()->getClassId();
        } elseif ($object instanceof \OpenDxp\Model\DataObject\Objectbrick\Data\AbstractData) {
            $row[$srcCol] = $object->getObject()->getId();
            $row['ownertype'] = 'objectbrick';
            $row['ownername'] = $object->getFieldname();
            $row['position'] = $object->getType();

            $classId = $object->getObject()->getClassId();
        }
    }
}
