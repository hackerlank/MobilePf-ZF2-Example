<?php
return array(
    'log' => array(
        'auth-log' => array(
            'writers' => array(
                array(
                    'name' => 'stream',
                    'options' => array(
                        'stream' => __DIR__ . '/../../logs/auth/%pattern%.log'
                    )
                )
            )
        ),
        'pay-log' => array(
            'writers' => array(
                array(
                    'name' => 'stream',
                    'options' => array(
                        'stream' => __DIR__ . '/../../logs/pay/%pattern%.log'
                    )
                )
            )
        )
    )
);