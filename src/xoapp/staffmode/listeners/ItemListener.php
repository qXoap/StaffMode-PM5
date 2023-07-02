<?php

namespace xoapp\staffmode\listeners;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\player\Player;
use pocketmine\world\Position;
use xoapp\staffmode\forms\TeleportMenu;
use xoapp\staffmode\items\Freeze;
use xoapp\staffmode\items\PlayerInfo;
use xoapp\staffmode\items\RandomTeleport;
use xoapp\staffmode\items\Teleport;
use xoapp\staffmode\items\UnVanish;
use xoapp\staffmode\items\Vanish;
use xoapp\staffmode\session\SessionFactory;
use xoapp\staffmode\session\SessionUtils;
use xoapp\staffmode\utils\Prefixes;

class ItemListener implements Listener {

    public function onItemUse(PlayerItemUseEvent $event)
    {
        $player = $event->getPlayer();

        $item = $event->getItem();

        if ($item instanceof Teleport) {
            if (!SessionFactory::isRegistered($player)) {
                return;
            }

            $player->sendForm(new TeleportMenu());
            return;
        }

        if ($item instanceof Vanish) {
            if (!SessionFactory::isRegistered($player)) {
                return;
            }

            SessionFactory::sendVanish($player);
            $player->sendMessage(Prefixes::PLUGIN . "You have entered in Vanish Mode");
            $player->getInventory()->setItem(4, new UnVanish());
            foreach (SessionUtils::getPlayers() as $players) {
                if (SessionFactory::isVanish($players)) {
                    $player->showPlayer($players);
                    $players->showPlayer($player);
                } else {
                    $players->hidePlayer($player);
                }
            }
            return;
        }

        if ($item instanceof UnVanish) {
            if (!SessionFactory::isRegistered($player)) {
                return;
            }

            SessionFactory::cancelVanish($player);
            $player->sendMessage(Prefixes::PLUGIN . "You have exited Vanish Mode");
            $player->getInventory()->setItem(4, new Vanish());
            $player->getEffects()->clear();
            foreach (SessionUtils::getPlayers() as $players) {
                if (SessionFactory::isVanish($players)) {
                    $player->hidePlayer($players);
                } else {
                    $players->showPlayer($player);
                }
            }
            return;
        }

        if ($item instanceof RandomTeleport) {
            if (!SessionFactory::isRegistered($player)) {
                return;
            }

            $players_count = count(SessionUtils::getPlayers());

            if ($players_count > 1) {

                $random = array_rand(SessionUtils::getPlayers());
                $random_player = SessionUtils::getPlayers()[$random];

                if ($random_player !== null) {

                    if (SessionUtils::equals($random_player->getName(), $player->getName())) {
                        $player->sendMessage(Prefixes::PLUGIN . "You can't teleport to yourself!");
                        return;
                    }

                    $x = $random_player->getPosition()->getX();
                    $y = $random_player->getPosition()->getY();
                    $z = $random_player->getPosition()->getZ();
                    $world = $random_player->getPosition()->getWorld();
                    $player->teleport(new Position($x, $y, $z, $world));
                    $player->sendMessage(Prefixes::PLUGIN . "You have successfully teleported to §a" . $random_player->getName());
                    return;
                }

                $player->sendMessage(Prefixes::PLUGIN . "An error occurred while trying to teleport!");
                return;
            }

            $player->sendMessage(Prefixes::PLUGIN . "Not enough players connected");
        }
    }

    public function onPlayerHit(EntityDamageByEntityEvent $event)
    {
        $victim = $event->getEntity();
        $player = $event->getDamager();

        if (!$victim instanceof Player) return;
        if (!$player instanceof Player) return;

        $item = $player->getInventory()->getItemInHand();

        if ($item instanceof Freeze) {
            if (!SessionFactory::isRegistered($player)) {
                $event->cancel();
                return;
            }

            if (SessionFactory::isRegistered($victim)) {
                $event->cancel();
                return;
            }

            if (!SessionFactory::isFreeze($victim)) {
                SessionFactory::sendFreeze($victim);
                SessionUtils::broadcastMessage(Prefixes::FREEZE . "Player §a" . $victim->getName() . " §7Was frozen by §e" . $player->getName());
                $event->cancel();
                return;
            }

            SessionFactory::cancelFreeze($victim);
            SessionUtils::broadcastMessage(Prefixes::FREEZE . "Player §a" . $victim->getName() . " §7Was thawed by §e" . $player->getName());
            $victim->getEffects()->clear();
            $event->cancel();
            return;
        }

        if ($item instanceof PlayerInfo) {
            if (!SessionFactory::isRegistered($player)) {
                $event->cancel();
                return;
            }

            if (SessionFactory::isRegistered($victim)) {
                $event->cancel();
                return;
            }

            $address = $victim->getNetworkSession()->getIp();
            $input = SessionUtils::getInputMode($victim);
            $platform = SessionUtils::getDeviceOS($victim);
            $device = $victim->getPlayerInfo()->getExtraData()["DeviceModel"];

            $player->sendMessage(Prefixes::OSINT . "Player Osint: §a" . $victim->getName());
            $player->sendMessage(" ");
            $player->sendMessage("§f- §7Device Model: §a" . $device);
            $player->sendMessage("§f- §7Platform: §a" . $platform);
            $player->sendMessage("§f- §7Player input: §a" . $input);
            $player->sendMessage("§f- §7Player Address: §a" . $address);
            $player->sendMessage(" ");

            $event->cancel();
            return;
        }

        if (SessionFactory::isRegistered($player)) {
            $event->cancel();
            return;
        }
    }

    public function onMove(PlayerMoveEvent $event): void
    {
        $player = $event->getPlayer();


        if (SessionFactory::isFreeze($player)) {
            $event->cancel();
            return;
        }
    }
}