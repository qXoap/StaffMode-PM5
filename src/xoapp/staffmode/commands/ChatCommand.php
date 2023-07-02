<?php

namespace xoapp\staffmode\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use xoapp\staffmode\session\SessionFactory;
use xoapp\staffmode\utils\Permissions;
use xoapp\staffmode\utils\Prefixes;

class ChatCommand extends Command {

    public function __construct()
    {
        parent::__construct("staffchat");

        $this->setPermission(Permissions::CHAT_COMMAND);

        $this->setAliases(["sc", "uchat"]);

        $this->setDescription("UltimateStaff Chat Command");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args): void
    {
        if (!$player instanceof Player) return;

        if (!$this->testPermission($player)) {
            $player->sendMessage(Prefixes::PLUGIN . "You dont have permission to use this");
            return;
        }

        if (!SessionFactory::isChat($player)) {
            SessionFactory::sendChat($player);
            $player->sendMessage(Prefixes::PLUGIN . "You have entered in StaffChat");
            return;
        }

        if (SessionFactory::isChat($player)) {
            SessionFactory::cancelChat($player);
            $player->sendMessage(Prefixes::PLUGIN . "You have exited the StaffChat");
            return;
        }
    }
}