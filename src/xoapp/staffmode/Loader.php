<?php

namespace xoapp\staffmode;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use xoapp\staffmode\commands\ChatCommand;
use xoapp\staffmode\commands\FreezeCommand;
use xoapp\staffmode\commands\StaffCommand;
use xoapp\staffmode\listeners\ItemListener;
use xoapp\staffmode\listeners\StaffListener;
use xoapp\staffmode\scheduler\MainScheduler;

class Loader extends PluginBase {
    use SingletonTrait;

    protected function onEnable(): void
    {
        self::setInstance($this);

        $this->getServer()->getCommandMap()->registerAll("UltimateStaff", [
            new StaffCommand(),
            new ChatCommand(),
            new FreezeCommand()
        ]);

        $this->getServer()->getPluginManager()->registerEvents(new ItemListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new StaffListener(), $this);

        $this->getScheduler()->scheduleRepeatingTask(new MainScheduler(), 20);
    }
}