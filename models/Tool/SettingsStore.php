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

namespace OpenDxp\Model\Tool;

use Exception;
use OpenDxp\Model;
use OpenDxp\Model\Tool\SettingsStore\Dao;

/**
 * @method Dao getDao()
 */
final class SettingsStore extends Model\AbstractModel
{
    public const string TYPE_BOOLEAN = 'bool';

    public const string TYPE_FLOAT = 'float';

    public const string TYPE_INTEGER = 'int';

    public const string TYPE_STRING = 'string';

    protected const array ALLOWED_TYPES = [
        self::TYPE_BOOLEAN,
        self::TYPE_FLOAT,
        self::TYPE_INTEGER,
        self::TYPE_STRING,
    ];

    /**
     * @internal
     */
    protected string $id;

    /**
     * @internal
     */
    protected ?string $scope = null;

    /**
     * @internal
     */
    protected string $type = '';

    /**
     * @internal
     */
    protected mixed $data = null;

    /**
     * @internal
     */
    protected static ?self $instance = null;

    private static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @throws Exception
     */
    private static function validateType(string $type): bool
    {
        if (!in_array($type, self::ALLOWED_TYPES)) {
            throw new Exception(sprintf('Invalid type `%s`, allowed types are %s', $type, implode(',', self::ALLOWED_TYPES)));
        }

        return true;
    }

    /**
     * @throws Exception
     */
    public static function set(string $id, float|bool|int|string $data, string $type = 'string', ?string $scope = null): bool
    {
        self::validateType($type);
        $instance = self::getInstance();

        return $instance->getDao()->set($id, $data, $type, $scope);
    }

    public static function delete(string $id, ?string $scope = null): int|string
    {
        $instance = self::getInstance();

        return $instance->getDao()->delete($id, $scope);
    }

    public static function get(string $id, ?string $scope = null): ?SettingsStore
    {
        try {
            $item = new self();
            $item->getDao()->getById($id, $scope);

            return $item;
        } catch (Model\Exception\NotFoundException) {
            return null;
        }
    }

    /**
     * @return string[]
     */
    public static function getIdsByScope(string $scope): array
    {
        $instance = self::getInstance();

        return $instance->getDao()->getIdsByScope($scope);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getScope(): ?string
    {
        return $this->scope;
    }

    public function setScope(?string $scope): void
    {
        $this->scope = (string) $scope;
    }

    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @throws Exception
     */
    public function setType(string $type): void
    {
        self::validateType($type);
        $this->type = $type;
    }

    public function getData(): float|bool|int|string
    {
        return $this->data;
    }

    public function setData(float|bool|int|string $data): void
    {
        if (!empty($this->getType())) {
            settype($data, $this->getType());
        }
        $this->data = $data;
    }
}
