<?php

namespace AppBundle\Stacksight\EntityEvents\interfaces;

interface StackSightEntityEventInterface{

    public static function getEntity();

    public function sendEvent($stacksight, $router, $type, $object);

    public function messageCreate($data);

    public function messageUpdate($data);

    public function messageDelete($data);
}
