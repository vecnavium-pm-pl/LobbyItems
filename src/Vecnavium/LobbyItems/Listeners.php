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

class Listeners implements Listener {


    public function onJoin(PlayerJoinEvent $event) {
        foreach (Main::getInstance()->lobbyitemconfigurations()->get("lobby-items") as $item) {
            Main::getInstance()->giveItems($event->getPlayer(), explode("-", $item));
        }
    }

    public function onInteract(PlayerInteractEvent $event) {
        $itemid = $event->getItem()->getId();
        $itemname = $event->getItem()->getName();
        foreach (Main::getInstance()->lobbyitemconfigurations()->get("lobby-items") as $item) {
            $get = explode("-", $item);
            if ($itemid == $get[0] && $itemname === "$get[4]") Main::getInstance()->getServer()->dispatchCommand($event->getPlayer(), "$get[5]");
        }
    }

    public function onDrop(PlayerDropItemEvent $event) {
        if(Main::getInstance()->lobbyitemconfigurations()->getNested("events.drop-inventory-items") == false) $event->cancel();
    }

    public function onInventoryMove(InventoryTransactionEvent $event) {
        if(Main::getInstance()->lobbyitemconfigurations()->getNested("events.move-inventory-items") == false) $event->cancel();
    }

}