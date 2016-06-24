<?php

namespace Linnovate\Stacksight;

use Linnovate\Stacksight\Stacksight;
use Doctrine\ORM\Event\LifecycleEventArgs;

class StacksightDoctrineEvents
{

    private $stacksight;

    public function __construct(Stacksight $stacksight)
    {
        $this->stacksight = $stacksight;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();
    }
}