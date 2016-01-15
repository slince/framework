<?php
namespace DefaultApplication\Model\Entity;

use Cake\ORM\Entity;

/**
 * Link Entity.
 */
class Link extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'name' => true,
        'src' => true,
        'href' => true,
        'sort' => true,
        'status' => true,
        'create_time' => true,
        'modify_time' => true
    ];
}
