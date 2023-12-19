<?php

namespace xoapp\staffmode\items;

use pocketmine\block\utils\DyeColor;
use pocketmine\item\Dye;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemTypeIds;
use xoapp\staffmode\utils\ItemNames;

class UnVanish extends Dye {

    public function __construct()
    {
        parent::__construct(new ItemIdentifier(ItemTypeIds::DYE), ItemNames::UN_VANISH);
        $this->setCustomName(ItemNames::UN_VANISH);
        $this->setColor(DyeColor::GRAY());
    }
}