<?php

namespace lazyperson0710\pvp;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener {

    private array $worlds = [];

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $config = new Config($this->getDataFolder() . "config.yml", Config::YAML, [
            "pvpを許可するWorldNameを入力",
            "world2",
        ]);
        foreach ($config->getAll() as $item) {
            $this->worlds[] = $item;
        }
        var_dump($this->worlds);
    }

    public function onDamage(EntityDamageEvent $event) {
        $entity = $event->getEntity();
        //if (!$entity instanceof Player) {
        //プレイヤーだけDamageイベントを消したい場合は//を削除してください
        //    return;
        //}
        switch ($event->getCause()) {
            case EntityDamageEvent::CAUSE_ENTITY_ATTACK:
            case EntityDamageEvent::CAUSE_PROJECTILE:
                if ($entity->getWorld()->getFolderName() != in_array($entity->getWorld()->getFolderName(), $this->worlds)) {
                    $event->cancel();
                    if ($entity instanceof Player) {
                        $entity->sendTip("現在のワールドではDamageを与えることは出来ません");
                    }
                }
                break;
        }
    }
}