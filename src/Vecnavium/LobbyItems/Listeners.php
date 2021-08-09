<?php

namespace Vecnavium\LobbyItems;

use pocketmine\event\Listener;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;

class Listeners implements Listener {


    public function onJoin(PlayerJoinEvent $event) {
        $event->getPlayer()->getInventory()->clearAll();
        foreach (Main::getInstance()->lobbyitemconfigurations()->get("lobby-items") as $item) {
            Main::getInstance()->giveItems($event->getPlayer(), explode("-", $item));
        }
    }

    public function onInteract(PlayerInteractEvent $event) {
        $itemid = $event->getItem()->getId();
        $itemmeta = $event->getItem()->getDamage();
        $itemname = $event->getItem()->getName();
        foreach (Main::getInstance()->lobbyitemconfigurations()->get("lobby-items") as $item) {
            $get = explode("-", $item);
            if ($itemid == $get[0] && $itemmeta == $get[1] && $itemname === "$get[4]") Main::getInstance()->getServer()->dispatchCommand($event->getPlayer(), "$get[5]");
        }
    }

    public function onInventoryMove(InventoryTransactionEvent $event) {
        if(Main::getInstance()->lobbyitemconfigurations()->getNested("events.move-inventory-items") == false) $event->setCancelled(true);
    }

}