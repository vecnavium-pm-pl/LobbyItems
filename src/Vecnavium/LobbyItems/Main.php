<?php

namespace Vecnavium\LobbyItems;

use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\utils\Config;
use pocketmine\plugin\PluginBase;
use Vecnavium\EventListener;
use Vecnavium\LobbyItems\CheckUpdateTask;

class Main extends PluginBase {

    public static $instance;

    public static function getInstance(): Main
    {
        return self::$instance;
    }

    
    public function lobbyitemconfigurations() {
        return new Config($this->getDataFolder() . "config.yml", Config::YAML);
    }

    public function onEnable()
    {
        self::$instance = $this;
        $this->getServer()->getPluginManager()->registerEvents(new Listeners(), $this);
        $this->saveResource("config.yml");
        $this->checkUpdate();
    }

    public function checkUpdate(bool $isRetry = false): void {

        $this->getServer()->getAsyncPool()->submitTask(new CheckUpdateTask($this, $isRetry));
    }

    public function giveItems(Player $player, $get) {
        $player->getInventory()->setItem($get[3], Item::get($get[0], $get[1], $get[2])->setCustomName($get[4]));

    }

}