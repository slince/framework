<?php
return array(
    'cache' => array(
        'default' => array(
            'class' => 'Slince\\Cache\\Cache',
            'params' => array(
                'handler' => array(
                    'class' => 'Slince\\Cache\\Handler\\FileHandler',
                    'params' => array(
                        'path' =>  CACHE_PATH
                     )
                )
            ),
            'calls' => array(
                'setPath' => ''
            )
        )
    )
);