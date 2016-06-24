<?php

namespace Linnovate\Stacksight\EntityEvents;

use Linnovate\Stacksight\EntityEvents\interfaces\StackSightEntityEventInterface;
use Linnovate\Stacksight\EventListener\StacksightDoctrineSubscriber;

class StacksightPostEntityEvent implements StackSightEntityEventInterface
{
    const TARGET_ENTITY = 'AppBundle\Entity\Post';

    public function sendEvent($stacksight, $router, $type, $object){
        $data = array(
            'user_name' => $object->getAuthorEmail(),
            'title' => $object->getTitle(),
            'url' => $router->generate('blog_post', array('slug' => $object->getSlug()), true)
        );

        $event = array();
        $event['user'] = array(
            'name' => $data['user_name'],
            'url' => ''
        );

        switch($type){
            case StacksightDoctrineSubscriber::STACKSIGHT_OP_CREATE:
                $action = $this->messageCreate($data);
                break;
            case StacksightDoctrineSubscriber::STACKSIGHT_OP_UDATE:
                $action = $this->messageUpdate($data);
                break;
            case StacksightDoctrineSubscriber::STACKSIGHT_OP_DELETE:
                $action = $this->messageDelete($data);
                break;
            default:
                $action = false;
        }
        $ss_client = $stacksight->getClient();
        $ss_client->publishEvent(array(
                'action' => $action,
                'type' => 'content',
                'subtype' => 'post',
                'name' => $data['title'],
                'url' => $data['url']
            ) + $event);
    }

    public function messageCreate($data){
        return 'created';
    }

    public function messageUpdate($data){
        return 'updated';
    }

    public function messageDelete($data){
        return 'deleted';
    }

    public static function getEntity(){
        return self::TARGET_ENTITY;
    }
}