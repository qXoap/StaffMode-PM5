<?php

namespace xoapp\staffmode\items;

use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemTypeIds;
use xoapp\staffmode\utils\ItemNames;

class PlayerInfo extends Item {

    public function __construct()
    {
        parent::__construct(new ItemIdentifier(ItemTypeIds::STICK));
        $this->setCustomName(ItemNames::PLAYER_INFO);
    }
}