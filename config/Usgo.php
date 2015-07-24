<?php

return [
    'parser' => [
        'name'          => 'USGO',
        'enabled'       => true,
        'sender_map'    => [
            '/@USGOabuse.net/',
        ],
        'body_map'      => [
            //
        ],
    ],

    'feeds' => [
        'default' => [
            'class'     => 'SPAM',
            'type'      => 'Abuse',
            'enabled'   => true,
            'fields'    => [
                'Source-IP',
                'Feedback-Type',
                'Received-Date',
            ],
        ],

    ],
];
