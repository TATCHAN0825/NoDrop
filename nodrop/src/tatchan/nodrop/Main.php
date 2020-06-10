<?php

declare(strict_types=1);

namespace tatchan\nodrop;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\event\Player\PlayerDataSaveEvent;
use pocketmine\utils\Config;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\inventory\FurnaceBurnEvent;
use pocketmine\item\Item;
use pocketmine\event\inventory\CraftingManager;

class Main extends PluginBase implements Listener{

	public function onEnable(): void{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
	}

	public function onDrop(PlayerDropItemEvent $event){
		$player = $event->getPlayer();
		$item = $event->getItem();
		$id = $item->getId();
		$damage = $item->getDamage();
		if ($this->config->exists("{$id}:{$damage}")) {
			$event->setCancelled();
			$player->sendMessage("§c>>§fこのアイテムのドロップは禁止されてます");
		}
	}

	public function onCraft(CraftItemEvent $event){
		$id = $event->getInputs();
		foreach ($id as $item) {
			$id = $item->getId();
			$damage = $item->getDamage();
			if ($this->config->exists("{$id}:{$damage}")) {
				$event->setCancelled();
				$event->getPlayer()->sendMessage("§c>>§fこのアイテムのクラフトは禁止されてます");
			}
		}
	}

	public function onPlace(BlockPlaceEvent $event){
		$player = $event->getPlayer();
		$item = $event->getItem();
		$id = $item->getId();
		$damage = $item->getDamage();
		if ($this->config->exists("{$id}:{$damage}")) {
			$event->setCancelled();
			$player->sendMessage("§c>>§fこのアイテムの設置は禁止されてます");
		}
	}

	public function onChest(InventoryTransactionEvent $event){
		foreach($event->getTransaction()->getActions() as $act){
			if($this->getConfig()->exists("{$act->getTargetItem()->getID()}:{$act->getTargetItem()->getDamage()}")){
				$event->setCancelled();
				$event->getTransaction()->getSource()->getPlayer()->sendMessage("§c>>§fこのアイテムの精錬、チェストに預けるのは禁止されています");
			}
		}
	}

}

