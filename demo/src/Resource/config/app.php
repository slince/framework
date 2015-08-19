<?php
return array(
    'app' => array(
        'name' => 'slblog',
        'timezone' => 'Asia/shanghai',
        'bundles' => array(
            
            new Sl\Bundle\UserBundle\UserBundle(),
            
            new App\Bundle\AdminBundle\AdminBundle(),
            new App\Bundle\ApiBundle\ApiBundle()
        )
    )
);