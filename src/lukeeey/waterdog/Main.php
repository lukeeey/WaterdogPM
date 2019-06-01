<?php
declare ( strict_types = 1 );
namespace lukeeey\waterdog;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\network\mcpe\RakLibInterface;
use ReflectionProperty;
use ReflectionException;
use ReflectionClass;

class Main extends PluginBase implements Listener {

    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onPacketReceive(DataPacketReceiveEvent $event): void {
        $packet = $event->getPacket();
        if($packet instanceof LoginPacket) {   
                foreach ( $this->getServer()->getNetwork()->getInterfaces() as $interface ) {
						  if ( $interface instanceof RakLibInterface ) {
								try {
									 $reflector = new \ReflectionProperty( $interface, "interface" );
									 $reflector->setAccessible( true );
									 $reflector->getValue( $interface )->sendOption( "packetLimit", 900000000000 );
								} catch ( ReflectionException $e ) {}
						  }
					 }
            if(isset($packet->clientData["WaterDog_RemoteIP"])) {
                $class = new ReflectionClass($event->getPlayer());

                $prop = $class->getProperty("ip");
                $prop->setAccessible(true);
                $prop->setValue($event->getPlayer(), $packet->clientData["WaterDog_RemoteIP"]);
            }
        }
    }
}
