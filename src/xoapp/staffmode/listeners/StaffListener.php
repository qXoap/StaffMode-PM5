<?php

namespace xoapp\staffmode\listeners;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityCombustEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityItemPickupEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\server\CommandEvent;
use pocketmine\player\Player;
use pocketmine\Server;
use xoapp\staffmode\session\SessionFactory;
use xoapp\staffmode\session\SessionUtils;
use xoapp\staffmode\utils\Permissions;
use xoapp\staffmode\utils\Prefixes;

class StaffListener implements Listener
{

    public function onPlayerJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();

        foreach (SessionUtils::getPlayers() as $players) {
            if (SessionFactory::isVanish($players)) {
                $player->hidePlayer($players);
            }
        }
    }

    public function onQuit(PlayerQuitEvent $event)
    {
        $player = $event->getPlayer();

        if (SessionFactory::isFreeze($player)) {
            SessionFactory::cancelFreeze($player);
        }

        if (SessionFactory::isRegistered($player)) {
            $player->getInventory()->clearAll();
            $player->getArmorInventory()->clearAll();
            $player->getEffects()->clear();
            $player->setFlying(false);
            $player->setAllowFlight(false);
            $player->setSilent(false);
            SessionFactory::cancelVanish($player);
            SessionFactory::unregister($player);
            $player->getEffects()->clear();
            foreach (Server::getInstance()->getOnlinePlayers() as $players) {
                $players->showPlayer($player);
            }
        }
    }

    public function onExhaust(PlayerExhaustEvent $event)
    {
        $player = $event->getPlayer();

        if (!$player instanceof Player) return;

        if (SessionFactory::isRegistered($player)) {
            $event->cancel();
        }
    }

    public function onDeath(PlayerDeathEvent $event)
    {
        $player = $event->getPlayer();


        if (SessionFactory::isRegistered($player)) {
            $event->setDrops([]);
        }
    }

    public function onRespawn(PlayerRespawnEvent $event)
    {
        $player = $event->getPlayer();


        if (SessionFactory::isRegistered($player)) {
            SessionUtils::sendKit($player);
        }
    }

    public function onKick(PlayerKickEvent $event)
    {
        $player = $event->getPlayer();


        if (SessionFactory::isRegistered($player)) {
            $player->getInventory()->clearAll();
            $player->getArmorInventory()->clearAll();
            $player->getEffects()->clear();
            $player->extinguish();
            $player->setFlying(false);
            $player->setAllowFlight(false);
            SessionFactory::unregister($player);
            SessionFactory::cancelVanish($player);
            $player->getEffects()->clear();
            foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
                $onlinePlayer->showPlayer($player);
            }
        }
    }

    public function onDrop(PlayerDropItemEvent $event)
    {
        $player = $event->getPlayer();


        if (SessionFactory::isRegistered($player)) {
            $event->cancel();
        }
    }

    public function onInteract(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();


        if (SessionFactory::isRegistered($player)) {
            $event->cancel();
        }
    }

    public function onBreak(BlockBreakEvent $event)
    {
        $player = $event->getPlayer();


        if (SessionFactory::isRegistered($player)) {
            $event->cancel();
        }
    }

    public function onPlace(BlockPlaceEvent $event)
    {
        $player = $event->getPlayer();


        if (SessionFactory::isRegistered($player)) {
            $event->cancel();
        }
    }

    public function onDamage(EntityDamageEvent $event)
    {
        $entity = $event->getEntity();

        if (!$entity instanceof Player) return;

        if (SessionFactory::isRegistered($entity)) {
            $event->cancel();
            return;
        }

        if (SessionFactory::isFreeze($entity)) {
            $event->cancel();
            return;
        }
    }

    public function onDamageByEntity(EntityDamageByEntityEvent $event)
    {
        $entity = $event->getDamager();

        if (!$entity instanceof Player) return;

        if (SessionFactory::isFreeze($entity)) {
            $event->cancel();
        }
    }

    public function onPickup(EntityItemPickupEvent $event)
    {
        $entity = $event->getEntity();
        if ($entity instanceof Player) {
            if (SessionFactory::isRegistered($entity)) {
                $event->cancel();
            }
        }
    }

    public function onCombust(EntityCombustEvent $event)
    {
        $entity = $event->getEntity();
        if ($entity instanceof Player) {
            if (SessionFactory::isRegistered($entity)) {
                $event->cancel();
            }
        }
    }

    public function onCommand(CommandEvent $event)
    {
        $player = $event->getSender();

        if (!$player instanceof Player) return;

        if (SessionFactory::isFreeze($player)) {
            $event->cancel();
        }
    }

    public function onPlayerChat(PlayerChatEvent $event): void
    {
        $message = $event->getMessage();
        $player = $event->getPlayer();

        foreach (SessionUtils::getPlayers() as $players) {
            if ($players->hasPermission(Permissions::CHAT_COMMAND)) {
                if (SessionFactory::isChat($player)) {
                    $players->sendMessage(Prefixes::CHAT . $player->getName() . " : §7" . $message);
                    $event->cancel();
                }
            }
        }
    }
}