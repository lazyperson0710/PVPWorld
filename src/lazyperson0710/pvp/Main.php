<?php

namespace lazyperson0710\pvp;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener {

    private array $worlds = [];

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $config = new Config($this->getDataFolder() . "config.yml", Config::YAML, [
            "pvpを許可するWorld名" => ["world2"]
        ]);
        $this->worlds = $config->get("pvpを許可するWorld名");
    }

    public function onDamage(EntityDamageByEntityEvent $event) {
        $entity = $event->getEntity();
        //if (!$entity instanceof Player) {
        //プレイヤーだけDamageイベントを消したい場合は//を削除してください
        //    return;
        //}
        if (in_array($entity->getWorld()->getFolderName(), $this->worlds)) {
            $event->cancel();
            $damager = $event->getDamager();
            if ($entity instanceof Player && $damager instanceof Player) {
                $damager->sendTip("現在のワールドではDamageを与えることは出来ません");
            }
        }

    }
}
