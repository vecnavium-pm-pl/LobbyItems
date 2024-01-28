<?php

/**
 * Copyright (c) 2021 Vecnavium
 * LobbyItems is licensed under the GNU Lesser General Public License v3.0
 * GitHub: https://github.com/Vecnavium\LobbyItems
 */

namespace Vecnavium\LobbyItems;

use pocketmine\event\Listener;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\item\Item;

class Listeners implements Listener {

    public function onJoin(PlayerJoinEvent $event) : void{
        foreach (Main::getInstance()->getConfig()->get("lobby-items") as $item) {
            Main::getInstance()->giveItems($event->getPlayer(), explode("-", $item));
        }
    }

    public function onInteract(PlayerInteractEvent $event) : void{
        $player = $event->getPlayer();
        $itemA = $event->getItem();
        if($itemA->getNamedTag()->getTag(Main::TAG_LOBBY_ITEM) === null) return;
        foreach(Main::getInstance()->getLobbyItems() as $itemB){
            /** @var Item $itemB */
            if($itemA->equals($itemB, false, true)){
                Main::getInstance()->getServer()->dispatchCommand($player, $itemB->getNamedTag()->getTag(Main::TAG_ITEM_COMMAND)->getValue());
                break;
            }
        }
    }

    public function onDrop(PlayerDropItemEvent $event) : void{
        if(Main::getInstance()->getConfig()->getNested("events.drop-inventory-items") == false) $event->cancel();
    }

    public function onInventoryMove(InventoryTransactionEvent $event) : void{
        if(Main::getInstance()->getConfig()->getNested("events.move-inventory-items") == false) $event->cancel();
    }
}