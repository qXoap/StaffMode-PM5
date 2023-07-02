<?php

namespace xoapp\staffmode\items;

use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemTypeIds;
use xoapp\staffmode\utils\ItemNames;

class RandomTeleport extends Item {

    public function __construct()
    {
        parent::__construct(new ItemIdentifier(ItemTypeIds::CLOCK));
        $this->setCustomName(ItemNames::RANDOM_TELEPORT);
    }
}