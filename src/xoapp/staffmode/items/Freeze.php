<?php

namespace xoapp\staffmode\items;

use pocketmine\block\BlockTypeIds;
use pocketmine\data\bedrock\item\BlockItemIdMap;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use xoapp\staffmode\utils\ItemNames;

class Freeze extends Item {

    public function __construct()
    {
        parent::__construct(new ItemIdentifier(BlockItemIdMap::getInstance()->lookupItemId(BlockTypeIds::ICE)));
        $this->setCustomName(ItemNames::FREEZE);
    }
}