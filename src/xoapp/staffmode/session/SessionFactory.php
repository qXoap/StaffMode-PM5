<?php

namespace xoapp\staffmode\session;

use pocketmine\player\Player;

class SessionFactory {

    private static array $session;
    private static array $freeze;
    private static array $vanish;
    private static array $staffchat;

    private static array $items;
    private static array $offhand;
    private static array $armor;

    public static function register(Player $player): void
    {
        self::$session[$player->getName()] = new Session($player);
        self::$items[$player->getName()] = $player->getInventory()->getContents();
        self::$offhand[$player->getName()] = $player->getOffHandInventory()->getContents();
        self::$armor[$player->getName()] = $player->getArmorInventory()->getContents();
        $player->getInventory()->clearAll();
        $player->getOffHandInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        SessionUtils::sendKit($player);
    }

    public static function unregister(Player $player): void
    {
        unset(self::$session[$player->getName()]);
        $player->getInventory()->clearAll();
        $player->getOffHandInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        self::cancelVanish($player);
        $player->getInventory()->setContents(self::$items[$player->getName()]);
        $player->getOffHandInventory()->setContents(self::$offhand[$player->getName()]);
        $player->getArmorInventory()->setContents(self::$armor[$player->getName()]);
    }

    public static function isRegistered(Player $player): bool
    {
        return isset(self::$session[$player->getName()]);
    }

    public static function sendVanish(Player $player): void
    {
        self::$vanish[$player->getName()] = new Session($player);
    }

    public static function isVanish(Player $player): bool
    {
        return isset(self::$vanish[$player->getName()]);
    }

    public static function cancelVanish(Player $player): void
    {
        unset(self::$vanish[$player->getName()]);
    }

    public static function sendFreeze(Player $player): void
    {
        self::$freeze[$player->getName()] = new Session($player);
    }

    public static function isFreeze(Player $player): bool
    {
        return isset(self::$freeze[$player->getName()]);
    }

    public static function cancelFreeze(Player $player): void
    {
        unset(self::$freeze[$player->getName()]);
    }

    public static function sendChat(Player $player): void
    {
        self::$staffchat[$player->getName()] = new Session($player);
    }

    public static function isChat(Player $player): bool
    {
        return isset(self::$staffchat[$player->getName()]);
    }

    public static function cancelChat(Player $player): void
    {
        unset(self::$staffchat[$player->getName()]);
    }
}