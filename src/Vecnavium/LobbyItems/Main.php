<?php

/**
 * Copyright (c) 2021 Vecnavium
 * LobbyItems is licensed under the GNU Lesser General Public License v3.0
 * GitHub: https://github.com/Vecnavium\LobbyItems
 */

namespace Vecnavium\LobbyItems;

use pocketmine\player\Player;
use pocketmine\item\ItemFactory;
use pocketmine\utils\Config;
use pocketmine\plugin\PluginBase;
use Vecnavium\EventListener;

class Main extends PluginBase {

    public static $instance;

    public static function getInstance(): Main
    {
        return self::$instance;
    }

    
    public function lobbyitemconfigurations() {
        return new Config($this->getDataFolder() . "config.yml", Config::YAML);
    }

    public function onEnable(): void
    {
        self::$instance = $this;
        $this->getServer()->getPluginManager()->registerEvents(new Listeners(), $this);
        $this->saveResource("config.yml");
    }

    public function giveItems(Player $player, $get) {
        $player->getInventory()->setItem($get[3], ItemFactory::getInstance()->get($get[0], $get[1], $get[2])->setCustomName($get[4]));

    }

}