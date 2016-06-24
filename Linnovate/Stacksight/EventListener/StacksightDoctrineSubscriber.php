<?php

namespace Linnovate\Stacksight\EventListener;

use Linnovate\Stacksight\Stacksight;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

class StacksightDoctrineSubscriber  implements EventSubscriber
{
    private $stacksight;
    private $entity = false;
    private $operation = false;

    private $router;

    const STACKSIGHT_OP_CREATE = 'insert';
    const STACKSIGHT_OP_READ = 'read';
    const STACKSIGHT_OP_UDATE = 'update';
    const STACKSIGHT_OP_DELETE = 'delete';

    public function __construct(Stacksight $stacksight, $router, $classes = [])
    {
        $this->stacksight = $stacksight;
        $this->router = $router;
        if($classes){
            foreach($classes as $class){
                $this->entity[] = new $class();
            }
        }
    }

    public function getSubscribedEvents()
    {
        return array(
            'postPersist',
            'postUpdate',
            'postRemove'
        );
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->operation = self::STACKSIGHT_OP_UDATE;
        $this->index($args);
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->operation = self::STACKSIGHT_OP_CREATE;
        $this->index($args);
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $this->operation = self::STACKSIGHT_OP_DELETE;
        $this->index($args);
    }

    public function index(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = false;
        $stacksight_entity = false;
        if($this->entity){
            foreach($this->entity as $entity_class){
                $target_entity_class = $entity_class::getEntity();
                if($entity instanceof $target_entity_class){
                    $entityManager = $args->getEntityManager();
                    $entityStacksight =  $entity_class;
                }
            }
        }

        if($entityManager){
            $object =  $args->getObject();
            $entityStacksight->sendEvent($this->stacksight, $this->router, $this->operation, $object);
        }

    }
}