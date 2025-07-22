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

namespace OpenDxp\Model\User\Workspace;

use OpenDxp\Model;

/**
 * @internal
 *
 * @method \OpenDxp\Model\User\Workspace\Dao getDao()
 * @method void save()
 */
abstract class AbstractWorkspace extends Model\AbstractModel
{
    /**
     * @internal
     *
     */
    protected int $userId;

    /**
     * @internal
     *
     */
    protected int $cid;

    /**
     * @internal
     *
     */
    protected string $cpath;

    /**
     * @internal
     *
     */
    protected bool $list = false;

    /**
     * @internal
     *
     */
    protected bool $view = false;

    /**
     * @internal
     *
     */
    protected bool $publish = false;

    /**
     * @internal
     *
     */
    protected bool $delete = false;

    /**
     * @internal
     *
     */
    protected bool $rename = false;

    /**
     * @internal
     *
     */
    protected bool $create = false;

    /**
     * @internal
     *
     */
    protected bool $settings = false;

    /**
     * @internal
     *
     */
    protected bool $versions = false;

    /**
     * @internal
     *
     */
    protected bool $properties = false;

    /**
     * @return $this
     */
    public function setCreate(bool $create): static
    {
        $this->create = $create;

        return $this;
    }

    public function getCreate(): bool
    {
        return $this->create;
    }

    /**
     * @return $this
     */
    public function setDelete(bool $delete): static
    {
        $this->delete = $delete;

        return $this;
    }

    public function getDelete(): bool
    {
        return $this->delete;
    }

    /**
     * @return $this
     */
    public function setList(bool $list): static
    {
        $this->list = $list;

        return $this;
    }

    public function getList(): bool
    {
        return $this->list;
    }

    /**
     * @return $this
     */
    public function setProperties(bool $properties): static
    {
        $this->properties = $properties;

        return $this;
    }

    public function getProperties(): bool
    {
        return $this->properties;
    }

    /**
     * @return $this
     */
    public function setPublish(bool $publish): static
    {
        $this->publish = $publish;

        return $this;
    }

    public function getPublish(): bool
    {
        return $this->publish;
    }

    /**
     * @return $this
     */
    public function setRename(bool $rename): static
    {
        $this->rename = $rename;

        return $this;
    }

    public function getRename(): bool
    {
        return $this->rename;
    }

    /**
     * @return $this
     */
    public function setSettings(bool $settings): static
    {
        $this->settings = $settings;

        return $this;
    }

    public function getSettings(): bool
    {
        return $this->settings;
    }

    /**
     * @return $this
     */
    public function setVersions(bool $versions): static
    {
        $this->versions = $versions;

        return $this;
    }

    public function getVersions(): bool
    {
        return $this->versions;
    }

    /**
     * @return $this
     */
    public function setView(bool $view): static
    {
        $this->view = $view;

        return $this;
    }

    public function getView(): bool
    {
        return $this->view;
    }

    /**
     * @return $this
     */
    public function setCid(int $cid): static
    {
        $this->cid = $cid;

        return $this;
    }

    public function getCid(): int
    {
        return $this->cid;
    }

    /**
     * @return $this
     */
    public function setUserId(int $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return $this
     */
    public function setCpath(string $cpath): static
    {
        $this->cpath = $cpath;

        return $this;
    }

    public function getCpath(): string
    {
        return $this->cpath;
    }
}
