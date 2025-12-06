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

namespace OpenDxp\Model\DataObject\ClassDefinition\Data;

use Exception;
use OpenDxp;
use OpenDxp\Model;
use OpenDxp\Model\DataObject;
use OpenDxp\Model\DataObject\ClassDefinition\Service;
use OpenDxp\Model\DataObject\Concrete;

class User extends Model\DataObject\ClassDefinition\Data\Select
{
    /**
     * @internal
     */
    public bool $unique = false;

    /**
     * @internal
     *
     * @return $this
     */
    protected function init(): static
    {
        //loads select list options
        $options = $this->getOptions();
        if (OpenDxp::inAdmin() || empty($options)) {
            $this->configureOptions();
        }

        return $this;
    }

    /**
     * @see ResourcePersistenceAwareInterface::getDataFromResource
     *
     * @param null|Model\DataObject\Concrete $object
     *
     */
    public function getDataFromResource(mixed $data, ?Concrete $object = null, array $params = []): ?string
    {
        if (!empty($data)) {
            try {
                $this->checkValidity($data, true, $params);
            } catch (Exception $e) {
                $data = null;
            }
        }

        return $data ? (string) $data : null;
    }

    /**
     * @see ResourcePersistenceAwareInterface::getDataForResource
     *
     * @param Model\DataObject\Concrete|null $object
     */
    public function getDataForResource(mixed $data, ?DataObject\Concrete $object = null, array $params = []): ?string
    {
        $this->init();
        if (!empty($data)) {
            try {
                $this->checkValidity($data, true, $params);
            } catch (Exception $e) {
                $data = null;
            }
        }

        return $data;
    }

    /**
     * @internal
     */
    public function configureOptions(): void
    {
        $list = new Model\User\Listing();
        $list->setOrder('asc');
        $list->setOrderKey('name');

        $options = [];
        foreach ($list->load() as $user) {
            if ($user instanceof Model\User) {
                $value = $user->getName();
                $first = $user->getFirstname();
                $last = $user->getLastname();
                if (!empty($first) || !empty($last)) {
                    $value .= ' (' . $first . ' ' . $last . ')';
                }
                $options[] = [
                    'value' => $user->getId(),
                    'key' => $value,
                ];
            }
        }

        $this->setOptions($options);
    }

    public function checkValidity(mixed $data, bool $omitMandatoryCheck = false, array $params = []): void
    {
        if (!$omitMandatoryCheck && $this->getMandatory() && empty($data)) {
            throw new Model\Element\ValidationException('Empty mandatory field [ '.$this->getName().' ]');
        }

        if (!empty($data)) {
            $user = Model\User::getById((int)$data);
            if (!$user instanceof Model\User) {
                throw new Model\Element\ValidationException('Invalid user reference');
            }
        }
    }

    public function getDataForSearchIndex(DataObject\Localizedfield|DataObject\Fieldcollection\Data\AbstractData|DataObject\Objectbrick\Data\AbstractData|DataObject\Concrete $object, array $params = []): string
    {
        return '';
    }

    public static function __set_state(array $data): static
    {
        $obj = parent::__set_state($data);

        if (OpenDxp::inAdmin()) {
            $obj->configureOptions();
        }

        return $obj;
    }

    public function __sleep(): array
    {
        $vars = get_object_vars($this);
        unset($vars['options']);

        return array_keys($vars);
    }

    public function __wakeup(): void
    {
        //loads select list options
        $this->init();
    }

    public function jsonSerialize(): mixed
    {
        if (Service::doRemoveDynamicOptions()) {
            $this->options = null;
        }

        return parent::jsonSerialize();
    }

    public function resolveBlockedVars(): array
    {
        $blockedVars = parent::resolveBlockedVars();
        $blockedVars[] = 'options';

        return $blockedVars;
    }

    public function getUnique(): bool
    {
        return $this->unique;
    }

    public function setUnique(bool $unique): void
    {
        $this->unique = $unique;
    }

    public function getFieldType(): string
    {
        return 'user';
    }
}
