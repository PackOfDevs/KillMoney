<?php
        // __  __ _____ ____   ____                 _             
       // |  \/  |  ___|  _ \ / ___| __ _ _ __ ___ (_)_ __   __ _ 
      //  | |\/| | |_  | | | | |  _ / _` | '_ ` _ \| | '_ \ / _` |
     //   | |  | |  _| | |_| | |_| | (_| | | | | | | | | | | (_| |
    //    |_|  |_|_|   |____/ \____|\__,_|_| |_| |_|_|_| |_|\__, |
   //                                                        |___/ 
  //      Licensed under the Apache License, Version 2.0 (the "License")
 //       you may not use this file except in compliance with the License.
//        You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0

namespace mfdgaming\killmoney;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class KillMoney extends PluginBase implements Listener {
	public $config;

	public function onEnable() {
		$this->getLogger()->info("Thanks for using KillMoney by MFDGaming!");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$dataFolder = $this->getDataFolder();
		if(!is_dir($dataFolder)) {
			@mkdir($dataFolder);
		}
		$this->saveDefaultConfig();
		$this->config = (new Config($dataFolder . "config.yml", Config::YAML))->getAll();
	}

	public function onKill(PlayerDeathEvent $event) {
		$deadPlayer = $event->getPlayer();
		$damageCause = $deadPlayer->getLastDamageCause();
		$killer = $damageCause->getDamager();
		if($damageCause instanceof EntityDamageByEntityEvent) {
			if($killer instanceof Player) {
				$this->getServer()->getPluginManager()->getPlugin("EconomyAPI")->addMoney($killer, ((float) $this->config["kill-money-ammount"]));
				$killer->sendMessage($this->config["kill-message"]);
			}
		}
	}
}
