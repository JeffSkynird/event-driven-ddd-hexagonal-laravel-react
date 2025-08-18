<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Define which configuration should be used
    |--------------------------------------------------------------------------
    */

    'use' => env('AMQP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | AMQP properties separated by key
    |--------------------------------------------------------------------------
    */

    'properties' => [

        'production' => [
            'host'                  => env('AMQP_HOST', '127.0.0.1'),
            'port'                  => env('AMQP_PORT', 5672),
            'username'              => env('AMQP_USER', 'guest'),
            'password'              => env('AMQP_PASSWORD', 'guest'),
            'vhost'                 => env('AMQP_VHOST', '/'),
            'connect_options'       => [],
            'ssl_options' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],

            'exchange'              => 'amq.topic',
            'exchange_type'         => 'topic',
            'exchange_passive'      => false,
            'exchange_durable'      => true,
            'exchange_auto_delete'  => false,
            'exchange_internal'     => false,
            'exchange_nowait'       => false,
            'exchange_properties'   => [],
            'heartbeat' => 60,  // 60 segundos
            'queue_force_declare'   => false,
            'queue_passive'         => false,
            'queue_durable'         => true,
            'queue_exclusive'       => false,
            'queue_auto_delete'     => false,
            'queue_nowait'          => false,
            'queue_properties'      => ['x-ha-policy' => ['S', 'all']],

            'consumer_tag'          => '',
            'consumer_no_local'     => false,
            'consumer_no_ack'       => false,
            'consumer_exclusive'    => false,
            'consumer_nowait'       => false,
            'consumer_properties'   => [],
            'timeout'               => 0,
            'persistent'            => true,
            'publish_timeout'       => 0, // Only applicable when a publish is marked as mandatory
            'qos'                   => false,
            'qos_prefetch_size'     => 0,
            'qos_prefetch_count'    => 1,
            'qos_a_global'          => false
        ],
        'local' => [
            'host'                  => env('AMQP_HOST', '127.0.0.1'),
            'port'                  => env('AMQP_PORT', 5672),
            'username'              => env('AMQP_USER', 'guest'),
            'password'              => env('AMQP_PASSWORD', 'guest'),
            'vhost'                 => env('AMQP_VHOST', '/'),
            'connect_options'       => [],
            'read_timeout' => 60, // Aumentar el timeout de lectura
            'heartbeat' => 30,   
            'exchange'              => 'amq.topic',
            'exchange_type'         => 'topic',
            'exchange_passive'      => false,
            'exchange_durable'      => true,
            'exchange_auto_delete'  => false,
            'exchange_internal'     => false,
            'exchange_nowait'       => false,
            'exchange_properties'   => [],
            'heartbeat' => 60,  // 60 segundos
            'queue_force_declare'   => false,
            'queue_passive'         => false,
            'queue_durable'         => true,
            'queue_exclusive'       => false,
            'queue_auto_delete'     => false,
            'queue_nowait'          => false,
            'queue_properties'      => ['x-ha-policy' => ['S', 'all']],

            'consumer_tag'          => '',
            'consumer_no_local'     => false,
            'consumer_no_ack'       => false,
            'consumer_exclusive'    => false,
            'consumer_nowait'       => false,
            'consumer_properties'   => [],
            'timeout'               => 0,
            'persistent'            => true,
            'publish_timeout'       => 0, // Only applicable when a publish is marked as mandatory
            'qos'                   => false,
            'qos_prefetch_size'     => 0,
            'qos_prefetch_count'    => 1,
            'qos_a_global'          => false
        ],
    ],

];
