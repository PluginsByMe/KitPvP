<?php
namespace TheRoyalBlock\KitPvP;
//Blocks
use pocketmine\block\Block;
//Command
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
//Entity
use pocketmine\entity\Entity;
use pocketmine\entity\Effect;
//Events
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\entity\EntityLevelChangeEvent; 
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
//Inventory
use pocketmine\inventory\ChestInventory;
use pocketmine\inventory\EnderChestInventory;
//Item
use pocketmine\item\Item;
use pocketmine\item\enchantment\Enchantment;
//Lang
//Level
use pocketmine\level\Level;
use pocketmine\level\Position;
//Math
use pocketmine\math\Vector3;
//Metadata
//Nbt
use pocketmine\nbt\NBT;
//Network
use pocketmine\network\Network;
//Permission
use pocketmine\permission\Permission;
//Plugin
use pocketmine\plugin\PluginBase;
//Scheduler
use pocketmine\scheduler\PluginTask;
//Tile
use pocketmine\tile\Sign;
use pocketmine\tile\Chest;
//Utils
use pocketmine\utils\TextFormat as C;
use pocketmine\utils\Config;
//Other
use pocketmine\Player;
use pocketmine\Server;
class KitPvP extends PluginBase implements Listener {
    public $prefix;
	
//=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=
    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
		@mkdir($this->getDataFolder());
		@mkdir($this->getDataFolder()."Players");
		@mkdir($this->getDataFolder()."Players/".strtolower($name{0}));
	        @mkdir($this->getDataFolder()."Players/c/console.yml");
		$this->saveResource("config.yml");
	    	$cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);
	   	$this->prefix = $cfg->get("Prefix");
        	$this->getServer()->getLogger()->info ($this->prefix."§aKitPvP enabled!");
    }
    public function onDisable() {
        $this->getServer()->getLogger()->info ($this->prefix."§cKitPvP disabled!");
  }
//=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=
	public function onDrop(PlayerDropItemEvent $event) {
        $event->setCancelled(true);
    }
	public function onDeath(PlayerDeathEvent $event){
		$entity = $event->getEntity();
		$cause = $entity->getLastDamageCause();
		$event->setDeathMessage("");
		if ($cause instanceof EntityDamageByEntityEvent) {
            		$killer = $cause->getDamager();
			if($killer instanceof Player){
				$name = $killer->getName();
				$TargetFile = new Config($this->getDataFolder()."Players/".strtolower($name{0})."/".strtolower($name).".yml", Config::YAML);	
				$targetcoins = $TargetFile->get("Coins");
				$newCoins = $targetcoins + 5;
				$TargetFile->set("Coins", $newCoins);
				$TargetFile->save();
				$Killer->sendMessage ($this->prefix."§aYou have killed the player: §b". $Entity->getName ().". §f->§6 +5 Coins") ;
      }
		}
	}
	
	public function onJoin(PlayerJoinEvent $event) {
		$player = $event->getPlayer();
		$name = $player->getName();
		
		@mkdir($this->getDataFolder());
		@mkdir($this->getDataFolder()."Players");
		@mkdir($this->getDataFolder()."Players/".strtolower($name{0}));
		@mkdir($this->getDataFolder()."Players/c/console.yml");
		
		$PlayerFile = new Config($this->getDataFolder()."Players/".strtolower($name{0})."/".strtolower($name).".yml", Config::YAML);
		
		if(empty($PlayerFile->get("Coins"))){
			$PlayerFile->set("Coins", 0);
		}
		if(empty($PlayerFile->get("Kits"))){
			$PlayerFile->set("Kits", array("Survivor"));
		}
		
		$PlayerFile->save();
    }
	
//=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=
	
    public function onCommand(CommandSender $sender, Command $cmd, $lable, array $args) {
		$dataFilezz = $this->getDataFolder() . strtolower($sender->getName());
		$name = $sender->getName();
		$PlayerFile = new Config($this->getDataFolder()."Players/".strtolower($name{0})."/".strtolower($name).".yml", Config::YAML);
		
		$kits = $PlayerFile->get("Kits",[]);
		$coins = $PlayerFile->get("Coins",[]);
		
        switch ($cmd->getName()) {
            case "kits":
				
				$sender->sendMessage("§7=====================================");
				$sender->sendMessage(" §7- §8survivor §7[§aPurchased§7]");
				
				if(in_array("Maniac", $kits)){
					$sender->sendMessage(" §7- §bmaniac §7[§aPurchased§7]");
				} else {
					$sender->sendMessage(" §7- §bmaniac §7[§c250 coins§7]");
				}
				if(in_array("Prisoner", $kits)){
					$sender->sendMessage(" §7- §cprisoner §7[§aPurchased§7]");
				} else {
					$sender->sendMessage(" §7- §cprisoner §7[§c500 coins§7]");
				}
				if(in_array("Solid", $kits)){
					$sender->sendMessage(" §7- §asolid §7[§aPurchased§7]");
				} else {
					$sender->sendMessage(" §7- §asolid §7[§c750 coins§7]");
				}
				if(in_array("Demolisher", $kits)){
					$sender->sendMessage(" §7- §4demolisher §7[§aPurchased§7]");
				} else {
					$sender->sendMessage(" §7- §4demolisher §7[§c7500 coins§7]");
				}
				if(in_array("Lucky", $kits)){
					$sender->sendMessage(" §7- §flucky §7[§aPurchased§7]");
				} else {
					$sender->sendMessage(" §7- §flucky §7[§c10000 coins§7]");
				}
				if(in_array("Mad", $kits)){
					$sender->sendMessage(" §7- §6mad §7[§aPurchased§7]");
				} else {
					$sender->sendMessage(" §7- §6mad §7[§c100,000 coins§7]");
				}
					$sender->sendMessage("                     ");
					$sender->sendMessage("§9Kit select§7:   ");
					$sender->sendMessage("§c/kit <KitName>    ");
					$sender->sendMessage("§dCASE SENSITIVE! (lowercase)    ");
					$sender->sendMessage("§7=====================================");
				
                break;
            case "coins":
				$sender->sendMessage($this->prefix."You have §6".$coins." §fCoins!");
				break;
            case "setcoins":
				if($sender->isOP()){
					if(!empty($args[0]) && !empty($args[1])){
						
						$targetname = $args[0];
						if(file_exists($this->getDataFolder()."Players/".strtolower($targetname{0})."/".strtolower($targetname).".yml")){
							$TargetFile = new Config($this->getDataFolder()."Players/".strtolower($targetname{0})."/".strtolower($targetname).".yml", Config::YAML);
							
							$TargetFile->set("Coins", (int) $args[1]);
							$TargetFile->save();
							
							$sender->sendMessage($this->prefix."You have set §6".$targetname."'s coins to ".$args[1]."!");
						} else {
							$sender->sendMessage("Player does not exist!");
						}
						
					} else {
						$sender->sendMessage("/setcoins <player> <amount>");
					}
				}
				break;
            case "addcoins":
				if($sender->isOP()){
					if(!empty($args[0]) && !empty($args[1])){
						
						$targetname = $args[0];
						if(file_exists($this->getDataFolder()."Players/".strtolower($targetname{0})."/".strtolower($targetname).".yml")){
							$TargetFile = new Config($this->getDataFolder()."Players/".strtolower($targetname{0})."/".strtolower($targetname).".yml", Config::YAML);
							
							$targetcoins = $TargetFile->get("Coins");
							$newCoins = $targetcoins + (int) $args[1];
							
							$TargetFile->set("Coins", (int) $newCoins);
							$TargetFile->save();
							
							$sender->sendMessage($this->prefix."You have given §6".$targetname."  ".$args[1]." §fcoins!");
						} else {
							$sender->sendMessage("Player does not exist!");
						}
						
					} else {
						$sender->sendMessage("/addcoins <player> <amount>");
					}
				}
				break;
            case "kit":
				if(!empty($args[0])){
					if (strtolower($args[0]) != "survivor" &&
							strtolower($args[0]) != "maniac" &&
							strtolower($args[0]) != "prisoner" &&
							strtolower($args[0]) != "solid" &&
							strtolower($args[0]) != "demolisher" &&
							strtolower($args[0]) != "lucky" &&
							strtolower($args[0]) != "mad") {
						$sender->sendMessage($this->prefix . "§cThe kit §e$args[0] §cdoes not exist or there is a spelling error.");
						$sender->sendMessage("§6-> §f/kits");
					} else {
						###Survivor###
						if (strtolower($args[0] == "Survivor" xor $args[0] == "survivor")) {
							if($sender instanceof Player){
								if(is_file($dataFilezz)) {
    								$data = yaml_parse_file($dataFilezz);
    								$lastTime = $data["last-execute-command"][$args[0]];
  								} else {
    								$lastTime = 0;
  								}
  								if(time() - $lastTime < 1200) { // Time in Seconds!!
    								$timeLeft = time() - $lastTime;
        							$sender->sendMessage("Please wait for your cooldown to expire! You last used your kit " . $timeLeft . " seconds ago, but you must wait 1200 seconds (20 minutes) until you may use it again!!");
    								return true;
  								}
  								$data["last-execute-command"][$args[0]]= time();
  								yaml_emit_file($dataFilezz, $data);
								$sender->sendMessage($this->prefix . "§fKit §o§l§8Survivor §r§frecieved");
								$sender->getInventory()->setHelmet(Item::get(298, 0, 1));
								$sender->getInventory()->setChestplate(Item::get(299, 0, 1));
								$sender->getInventory()->setLeggings(Item::get(300, 0, 1));
								$sender->getInventory()->setBoots(Item::get(301, 0, 1));
								$sender->getInventory()->addItem(Item::get(276, 0, 1));
								$sender->getInventory()->addItem(Item::get(322, 0, 6));
  								return true;
								
							} else {
								$sender->sendMessage($this->prefix . "§fKit only available ingame:D");
							}
						}
						###Maniac###
						elseif (strtolower($args[0]) == "Maniac" xor $args[0] == "maniac") {
							if(!in_array("Maniac", $kits)){
								if($coins >= 250){
									$newCoins = $coins - 250;
									$kits[] = "Maniac";
									$PlayerFile->set("Kits", $kits);
									$PlayerFile->set("Coins", $newCoins);
									$PlayerFile->save();
									$sender->sendMessage($this->prefix."§aYou have successfully purchased the kit §bManiac §afor§6 250 coins, you can now use it at any time with the command §f/kit maniac!");
								} else {
									$sender->sendMessage($this->prefix."§cYou do not have enough coins to buy the kit §bManiac");
									$missingcoins = 250 - $coins;
									$sender->sendMessage($this->prefix."Available Coins§7: §6".$coins);
									$sender->sendMessage($this->prefix."Missing Coins§7: §6".$missingcoins);
									$sender->sendMessage($this->prefix."Required Coins§7:§6 250");
								}
							} else { //If already bought:
								if($sender instanceof Player){
									 if(is_file($dataFilezz)) {
    									$data = yaml_parse_file($dataFilezz);
    									$lastTime = $data["last-execute-command"][$args[0]];
  									} else {
    									$lastTime = 0;
  									}
  									if(time() - $lastTime < 900) { // Time in Seconds!!
 									   $timeLeft = time() - $lastTime;
									    $sender->sendMessage("Please wait for your cooldown to expire! You last used your kit " . $timeLeft . " seconds ago, but you must wait 900 seconds (15 minutes) until you may use it again!!");
 									   return true;
 									 }
									  $data["last-execute-command"][$args[0]]= time();
									  yaml_emit_file($dataFilezz, $data);
									$sender->sendMessage($this->prefix . "§fKit §o§l§bManiac §r§frecieved");
									$sender->getInventory()->setHelmet(Item::get(302, 0, 1));
									$sender->getInventory()->setChestplate(Item::get(303, 0, 1));
									$sender->getInventory()->setLeggings(Item::get(304, 0, 1));
									$sender->getInventory()->setBoots(Item::get(305, 0, 1));
									$sender->getInventory()->addItem(Item::get(283, 0, 1));
									$sender->getInventory()->addItem(Item::get(322, 0, 3));
									return true;
								} else {
									$sender->sendMessage($this->prefix . "§fKit is only available ingame :D");
								}
							}
						}
						###Prisoner###
						elseif (strtolower($args[0]) == "Prisoner" xor $args[0] == "prisoner") {
							
							if(!in_array("Prisoner", $kits)){
								
								if($coins >= 500){
									
									$newCoins = $coins - 500;
									
									$kits[] = "Prisoner";
									$PlayerFile->set("Kits", $kits);
									$PlayerFile->set("Coins", $newCoins);
									
									$PlayerFile->save();
									
									$sender->sendMessage($this->prefix."§aYou have successfully purchased the kit §cPrisoner §afor§6 500 coins, you can use it at any time with the command §f/kit prisoner!");
									
								} else {
									$sender->sendMessage($this->prefix."§cYou do not have enough coins to buy the Kit §cPrisoner");
									
									$missingcoins = 500 - $coins;
									
									$sender->sendMessage($this->prefix."Available Coins§7: §6".$coins);
									$sender->sendMessage($this->prefix."Missing Coins§7: §6".$missingcoins);
									$sender->sendMessage($this->prefix."Required Coins§7:§6 500");
								}
								
							} else { //If already bought:
  
								if($sender instanceof Player){
									  if(is_file($dataFilezz)) {
									    $data = yaml_parse_file($dataFilezz);
									    $lastTime = $data["last-execute-command"][$args[0]];
 									 } else {
 									   $lastTime = 0;
 									 }
									  if(time() - $lastTime < 600) { // Time in Seconds!!
									    $timeLeft = time() - $lastTime;
									    $sender->sendMessage("Please wait for your cooldown to expire! You last used your kit " . $timeLeft . " seconds ago, but you must wait 600 seconds (10 minutes) until you may use it again!!");
									    return true;
									  }
									  $data["last-execute-command"][$args[0]]= time();
									  yaml_emit_file($dataFilezz, $data);
									$sender->sendMessage($this->prefix . "§fKit §o§l§cPrisoner §r§frecieved");
									$sender->getInventory()->addItem(Item::get(272, 0, 1));
									$sender->getInventory()->addItem(Item::get(322, 0, 1));
									$sender->getInventory()->setHelmet(Item::get(306, 0, 1));
									$sender->getInventory()->setChestplate(Item::get(307, 0, 1));
									$sender->getInventory()->setLeggings(Item::get(308, 0, 1));
									$sender->getInventory()->setBoots(Item::get(309, 0, 1));
									return true;
								} else {
									$sender->sendMessage($this->prefix . "§fKit only available ingame :D");
								}
							}
						}
						###Solid###
						elseif (strtolower($args[0]) == "Solid" xor $args[0] == "solid") {
							
							if(!in_array("Solid", $kits)){
								
								if($coins >= 750){
									
									$newCoins = $coins - 750;
									
									$kits[] = "Solid";
									$PlayerFile->set("Kits", $kits);
									$PlayerFile->set("Coins", $newCoins);
									
									$PlayerFile->save();
									
									$sender->sendMessage($this->prefix."§aYou have sucessfully purchased the Kit §aSolid §afor§6 750 coins, you can now use it at any time with the Command §f/kit solid!");
									
								} else {
									$sender->sendMessage($this->prefix."§cYou do not have enough coins to buy the kit §aSolid");
									
									$missingcoins = 750 - $coins;
									
									$sender->sendMessage($this->prefix."Available Coins§7: §6".$coins);
									$sender->sendMessage($this->prefix."Missing Coins§7: §6".$missingcoins);
									$sender->sendMessage($this->prefix."Required Coins§7:§6 750");
								}
								
							} else { //If already bought:
  
								if($sender instanceof Player){
									  if(is_file($dataFilezz)) {
									    $data = yaml_parse_file($dataFilezz);
									    $lastTime = $data["last-execute-command"][$args[0]];
 									 } else {
 									   $lastTime = 0;
 									 }
									  if(time() - $lastTime < 300) { // Time in Seconds!!
									    $timeLeft = time() - $lastTime;
									    $sender->sendMessage("Please wait for your cooldown to expire! You last used your kit " . $timeLeft . " seconds ago, but you must wait 300 seconds (5 minutes) until you may use it again!!");
									    return true;
									  }
									  $data["last-execute-command"][$args[0]]= time();
									  yaml_emit_file($dataFilezz, $data);
									$sender->sendMessage($this->prefix . "§fKit §o§l§aSolid §r§fuse");
									$sender->getInventory()->addItem(Item::get(268, 0, 1));
									$sender->getInventory()->addItem(Item::get(322, 0, 2));
									$sender->getInventory()->setHelmet(Item::get(310, 0, 1));
									$sender->getInventory()->setChestplate(Item::get(311, 1));
									$sender->getInventory()->setLeggings(Item::get(312, 0, 1));
									$sender->getInventory()->setBoots(Item::get(313, 0, 1));
									return true;
								} else {
									$sender->sendMessage($this->prefix . "§fKit only available ingame :D");
								}
							}
						}
						###Demolisher###
						elseif (strtolower($args[0]) == "Demolisher" xor $args[0] == "demolisher") {
							
							if(!in_array("Demolisher", $kits)){
								
								if($coins >= 7500){
									
									$newCoins = $coins - 7500;
									
									$kits[] = "Demolisher";
									$PlayerFile->set("Kits", $kits);
									$PlayerFile->set("Coins", $newCoins);
									
									$PlayerFile->save();
									
									$sender->sendMessage($this->prefix."§aYou have sucessfully purchased the kit §4Demolisher §afor§6 7500 coins, you can use it any time with the Command §f/kit demolisher!");
									
								} else {
									$sender->sendMessage($this->prefix."§cYou do not have enough coins to buy the kit §4Krieger");
									
									$missingcoins = 7500 - $coins;
									
									$sender->sendMessage($this->prefix."Available Coins§7: §6".$coins);
									$sender->sendMessage($this->prefix."Missing Coins§7: §6".$missingcoins);
									$sender->sendMessage($this->prefix."Required Coins§7:§6 7500");
								}
								
							} else { //If already bought:
  
								if($sender instanceof Player){
									  if(is_file($dataFilezz)) {
									    $data = yaml_parse_file($dataFilezz);
									    $lastTime = $data["last-execute-command"][$args[0]];
 									 } else {
 									   $lastTime = 0;
 									 }
									  if(time() - $lastTime < 300) { // Time in Seconds!!
									    $timeLeft = time() - $lastTime;
									    $sender->sendMessage("Please wait for your cooldown to expire! You last used your kit " . $timeLeft . " seconds ago, but you must wait 300 seconds (5 minutes) until you may use it again!!");
									    return true;
									  }
									  $data["last-execute-command"][$args[0]]= time();
									  yaml_emit_file($dataFilezz, $data);
									$sender->sendMessage($this->prefix . "§fKit §o§l§4Demolisher §r§frecieved");
									$enchantmentdem1 = Enchantment::getEnchantment(0);
									$enchantmentdem1->setLevel(1);
									$enchantmentdem2 = Enchantment::getEnchantment(9);
									$enchantmentdem2->setLevel(4);
									$helmet = Item::get(306, 0, 1);
									$chestplate = Item::get(307, 0, 1);
									$leggings = Item::get(308, 0, 1);
									$boots = Item::get(309, 0, 1);
									$sword = Item::get(283, 0, 1);
									$inv = $sender->getInventory();
									$helmet->addEnchantment($enchantmentdem1);
									$chestplate->addEnchantment($enchantmentdem1);
									$leggings->addEnchantment($enchantmentdem1);
									$boots->addEnchantment($enchantmentdem1);
									$sword->addEnchantment($enchantmentdem2);
									$inv->addItem($sword);
									$sender->getInventory()->addItem(Item::get(322, 0, 5));
									$inv->setHelmet($helmet);
									$inv->setChestplate($chestplate);
									$inv->setLeggings($leggings);
									$inv->setBoots($boots);
									return true;
								} else {
									$sender->sendMessage($this->prefix . "§fKit only available in game:D");
								}
							}
						}
						###Lucky###
						elseif (strtolower($args[0]) == "Lucky" xor $args[0] == "lucky") {
							
							if(!in_array("Lucky", $kits)){
								
								if($coins >= 10000){
									
									$newCoins = $coins - 10000;
									
									$kits[] = "Lucky";
									$PlayerFile->set("Kits", $kits);
									$PlayerFile->set("Coins", $newCoins);
									
									$PlayerFile->save();
									
									$sender->sendMessage($this->prefix."§aYou have sucessfully purchased the kit §fLucky §afor§6 10000 coins, you can use it anytime with the Command §f/kit lucky!");
									
								} else {
									$sender->sendMessage($this->prefix."§cYou do not have enough coins to purchase the kit §fLucky");
									
									$missingcoins = 10000 - $coins;
									
									$sender->sendMessage($this->prefix."Available Coins§7: §6".$coins);
									$sender->sendMessage($this->prefix."Missing Coins§7: §6".$missingcoins);
									$sender->sendMessage($this->prefix."Required Coins§7:§6 10000");
								}
								
							} else { //If already bought:
  
								if($sender instanceof Player){
									$enchantmentdem1 = Enchantment::getEnchantment(0);
									$enchantmentdem1->setLevel(1);
									$enchantmentdem2 = Enchantment::getEnchantment(21);
									$enchantmentdem2->setLevel(1);
									$helmet = Item::get(310, 0, 1);
									$chestplate = Item::get(311, 0, 1);
									$leggings = Item::get(312, 0, 1);
									$boots = Item::get(313, 0, 1);
									$sword = Item::get(272, 0, 1);
									$inv = $sender->getInventory();
									$helmet->addEnchantment($enchantmentdem1);
									$chestplate->addEnchantment($enchantmentdem1);
									$leggings->addEnchantment($enchantmentdem1);
									$boots->addEnchantment($enchantmentdem1);
									$sword->addEnchantment($enchantmentdem2);
									$inv->addItem($sword);
									$sender->getInventory()->addItem(Item::get(322, 0, 2));
									$inv->setHelmet($helmet);
									$inv->setChestplate($chestplate);
									$inv->setLeggings($leggings);
									$inv->setBoots($boots);
								} else {
									$sender->sendMessage($this->prefix . "§fKit only available ingame :D");
								}
							}
						}
						###Mad###
						elseif (strtolower($args[0]) == "Mad" xor $args[0] == "mad") {
							
							if(!in_array("Mad", $kits)){
								
								if($coins >= 100000){
									
									$newCoins = $coins - 100000;
									
									$kits[] = "Mad";
									$PlayerFile->set("Kits", $kits);
									$PlayerFile->set("Coins", $newCoins);
									
									$PlayerFile->save();
									
									$sender->sendMessage($this->prefix."§aYou have sucessfully purchased the kit §6Mad §afor§6 100000 coins, you can use it at any time with the Command §f/kit mad!");
									
								} else {
									$sender->sendMessage($this->prefix."§cYou do not have enough coins to purchase the kit §6Mad");
									
									$missingcoins = 100000 - $coins;
									
									$sender->sendMessage($this->prefix."Available Coins§7: §6".$coins);
									$sender->sendMessage($this->prefix."Missing Coins§7: §6".$missingcoins);
									$sender->sendMessage($this->prefix."Required Coins§7:§6 100000");
								}
								
							} else { //If already bought:
  
								if($sender instanceof Player){
									$sender->sendMessage($this->prefix . "§fKit §o§l§6Mad §r§frecieved");
									$sender->removeAllEffects();
									$sender->getInventory()->clearAll();
									$enchantmentdem1 = Enchantment::getEnchantment(0);
									$enchantmentdem1->setLevel(1);
									$enchantmentdem2 = Enchantment::getEnchantment(17);
									$enchantmentdem2->setLevel(3);
									$enchantmentdem3 = Enchantment::getEnchantment(9);
									$enchantmentdem3->setLevel(2);
									$enchantmentdem4 = Enchantment::getEnchantment(21);
									$enchantmentdem4->setLevel(1);
									$enchantmentdem5 = Enchantment::getEnchantment(10);
									$enchantmentdem5->setLevel(5);
									$helmet = Item::get(310, 0, 1);
									$chestplate = Item::get(311, 0, 1);
									$leggings = Item::get(312, 0, 1);
									$boots = Item::get(313, 0, 1);
									$sword = Item::get(276, 0, 1);
									$inv = $sender->getInventory();
									$helmet->addEnchantment($enchantmentdem1);
									$chestplate->addEnchantment($enchantmentdem1);
									$leggings->addEnchantment($enchantmentdem1);
									$boots->addEnchantment($enchantmentdem1);
									$helmet->addEnchantment($enchantmentdem2);
									$chestplate->addEnchantment($enchantmentdem2);
									$leggings->addEnchantment($enchantmentdem2);
									$boots->addEnchantment($enchantmentdem2);
									$sword->addEnchantment($enchantmentdem3);
									$sword->addEnchantment($enchantmentdem4);
									$sword->addEnchantment($enchantmentdem5);
									$inv->addItem($sword);
									$sender->getInventory()->addItem(Item::get(322, 0, 16));
									$inv->setHelmet($helmet);
									$inv->setChestplate($chestplate);
									$inv->setLeggings($leggings);
									$inv->setBoots($boots);
								} else {
									$sender->sendMessage($this->prefix . "§fKit only available ingame :D");
								}
							}
						}
					}
                } else {
					$sender->sendMessage("§6-> §f/kit <kitname>");
					$sender->sendMessage("§6-> §aList all of the available kits with §f/kits");
				}
                break;
            case "spawn":
                $sender->getInventory()->clearAll();
                $sender->removeAllEffects();
                $sender->setHealth(0); // Well, this works too!
                break;
            case "feed":
                if ($sender->isOP() && $sender instanceof Player) {
                    $sender->setFood(20);
                    $sender->sendMessage($this->prefix . "§aYour appetite has been quenched!");
                } else {
                    $sender->sendMessage($this->prefix . "§4You do not have permission to use this command!");
                }
                break;
            case "heal":
                if ($sender->isOP() && $sender instanceof Player) {
                    $sender->setHealth(20);
                    $sender->sendMessage($this->prefix . "§aYou now have full health!");
                } else {
                    $sender->sendMessage($this->prefix . "§4You do not have permission to use this command!");
                }
                break;
              case "gethealth":
				if($sender instanceof Player){
					$h = $sender->getHealth() /2;
					$sender->sendMessage("-> $h");
					$this->getLogger()->info("$name -> $h");
				}
		}
    }
	public function onMove(PlayerMoveEvent $event){
		$player = $event->getPlayer();
		$x = $player->getX();
		$y = $player->getY();
		$z = $player->getZ();
		$level = $player->getLevel();
		$block = $level->getBlock(new Vector3($x, $y-1, $z));
		if($block->getID() == 41){
			$direction = $player->getDirectionVector();
			$dx = $direction->getX();
			$dz = $direction->getZ();
			$player->knockBack($player, 0, $dx, $dz, 0.8);
			$player->setHealth(20);
		}
	}
	public function onBlockPlace(BlockPlaceEvent $event){
	$sender = $event->getPlayer();
	if($event->getBlock()->getID() == "87"){ //Netherrack->Fire Resistance, 5 mins
		$sender->addEffect(Effect::getEffect(12)->setAmplifier(0)->setDuration(6000)->setVisible(true));
		$event->setCancelled(true);
	}elseif($event->getBlock()->getID() == "175"){ //Sunflower->Speed 2, 2 mins
		$sender->addEffect(Effect::getEffect(1)->setAmplifier(2)->setDuration(2400)->setVisible(true));
		$event->setCancelled(true);
	}elseif($event->getBlock()->getID() == "170"){ //Hay Bale->Jump Boost, 30 secs
		$sender->addEffect(Effect::getEffect(8)->setAmplifier(0)->setDuration(600)->setVisible(true));
		$event->setCancelled(true);
	}else{
		$event->setCancelled(true);
	}
    }
	public function onBlockBreak(BlockBreakEvent $event){
		$event->setCancelled(true);
	}
}
?>
