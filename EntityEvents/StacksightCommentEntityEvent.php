<?php

namespace AppBundle\Stacksight\EntityEvents;

use AppBundle\Stacksight\EntityEvents\interfaces\StackSightEntityEventInterface;

class StacksightCommentEntityEvent  implements StackSightEntityEventInterface
{
    const TARGET_ENTITY = 'AppBundle\Entity\Comment';

    public function sendEvent($stacksight, $router, $type, $object){
        print_r($type);
    }

    public function messageCreate($data){

    }

    public function messageUpdate($data){

    }

    public function messageDelete($data){

    }

    public static function getEntity(){
        return self::TARGET_ENTITY;
    }
}