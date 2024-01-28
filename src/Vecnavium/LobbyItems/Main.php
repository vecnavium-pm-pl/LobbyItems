<?php

/**
 * Copyright (c) 2021 Vecnavium
 * LobbyItems is licensed under the GNU Lesser General Public License v3.0
 * GitHub: https://github.com/Vecnavium\LobbyItems
 */

namespace Vecnavium\LobbyItems;

use Exception;
use pocketmine\item\Item;
use pocketmine\player\Player;
use pocketmine\item\StringToItemParser;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    const TAG_LOBBY_ITEM = "LobbyItems"; //TAG_Int
    const TAG_ITEM_COMMAND = "ItemCommand"; //TAG_String

    public static $instance;

    public static function getInstance() : Main{
        return self::$instance;
    }

    protected function onEnable() : void{
        $this->saveDefaultConfig();
        self::$instance = $this;
        $this->getServer()->getPluginManager()->registerEvents(new Listeners(), $this);
    }

    public function giveItems(Player $player, array $get) : void{
        $item = self::parseItem($get);
        $player->getInventory()->setItem((int) $get[2], $item);
    }

    public static function parseItem(array $itemData) : Item{
        $item = StringToItemParser::getInstance()->parse((string) $itemData[0]);
        $command = "";
        if(isset($itemData[4])){
            $command = (string) $itemData[4];
        }
        if($item === null){
            throw new Exception("Failed to parse item name {$itemData[0]}.");
        }
        $item->setNamedTag(CompoundTag::create()->setTag(self::TAG_LOBBY_ITEM, new IntTag(1))->setTag(self::TAG_ITEM_COMMAND, new StringTag($command)));
        $item->setCustomName((string) $itemData[3])->setCount((int) $itemData[1]);
        return $item;
    }

    public function getLobbyItems() : array{
        $items = [];
        foreach($this->getConfig()->get("lobby-items") as $item){
            $item = self::parseItem(explode("-",$item));
            $items[] = $item;
        }
        return $items;
    }
}