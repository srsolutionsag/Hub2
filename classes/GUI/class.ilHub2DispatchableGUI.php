<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

use srag\Plugins\Hub2\Exception\HubException;

/**
 * @author            Fabian Schmid <fabian@sr.solutions>
 */
interface ilHub2DispatchableGUI
{
    public const CMD_INDEX = 'index';
    public function executeCommand(): void;
    public function index(): void;

    public function getActiveTab(): ?string;

    public function getDefaultClass(): ilHub2DispatchableGUI;

    public function getSubtabs(): array;
    public function getTabs(): array;

    public function getActiveSubTab(): ?string;

    /**
     * @throws HubException
     */
    public function checkAccess(): void;
}
