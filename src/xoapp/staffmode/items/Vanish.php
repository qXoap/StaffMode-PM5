<?php

namespace xoapp\staffmode\items;

use pocketmine\block\utils\DyeColor;
use pocketmine\item\Dye;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemTypeIds;
use xoapp\staffmode\utils\ItemNames;

class Vanish extends Dye {

    public function __construct()
    {
        parent::__construct(new ItemIdentifier(ItemTypeIds::DYE), ItemNames::VANISH);
        $this->setColor(DyeColor::GREEN());
    }
}