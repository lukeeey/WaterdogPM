<?php

namespace lukeeey\waterdog;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\LoginPacket;

class Main extends PluginBase implements Listener {

    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onPacketReceive(DataPacketReceiveEvent $event): void {
        $packet = $event->getPacket();
        if($packet instanceof LoginPacket) {
            if(isset($packet->clientData["WaterDog_RemoteIP"])) {
                $class = new \ReflectionClass($event->getPlayer());

                $prop = $class->getProperty("ip");
                $prop->setAccessible(true);
                $prop->setValue($event->getPlayer(), $packet->clientData["WaterDog_RemoteIP"]);
            }
        }
    }
}