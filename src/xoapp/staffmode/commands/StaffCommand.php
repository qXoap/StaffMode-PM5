<?php

namespace xoapp\staffmode\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use xoapp\staffmode\session\SessionFactory;
use xoapp\staffmode\utils\Permissions;
use xoapp\staffmode\utils\Prefixes;

class StaffCommand extends Command {

    public function __construct()
    {
        parent::__construct("staff");

        $this->setPermission(Permissions::MAIN_COMMAND);

        $this->setAliases(["mod", "umod", "staffmode"]);

        $this->setDescription("UltimateStaff Command");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args): void
    {
        if (!$player instanceof Player) return;

        if (!$this->testPermission($player)) {
            $player->sendMessage(Prefixes::PLUGIN . "You dont have permission to use this");
            return;
        }

        if (!SessionFactory::isRegistered($player)) {
            $player->setAllowFlight(true);
            SessionFactory::register($player);
            $player->setGamemode(GameMode::SURVIVAL());
            $player->setHealth($player->getMaxHealth());
            $player->getHungerManager()->setFood($player->getHungerManager()->getMaxFood());
            $player->sendMessage(Prefixes::PLUGIN . "You have entered in StaffMode");
            return;
        }

        SessionFactory::unregister($player);
        SessionFactory::cancelVanish($player);
        $player->setAllowFlight(false);
        $player->setFlying(false);
        $player->setGamemode(GameMode::SURVIVAL());
        $player->getEffects()->clear();
        $player->sendMessage(Prefixes::PLUGIN . "You have exited the StaffMode");
    }
}