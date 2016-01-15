<?php
namespace DefaultApplication\Model\Table;

use Cake\Event\Event;

trait TimeUpdateTrait
{

    function beforeSave(Event $event)
    {
        $entity = $event->data['entity'];
        if ($entity->isNew()) {
            $entity->create_time = $entity->modify_time = time();
        } else {
            $entity->modify_time = time();
        }
    }
}