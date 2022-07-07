<?php

return [
    'pools' => [
        'users' => [
            // Model that will be mentioned.
            'model' => App\User::class,

            // The column that will be used to search the model by the parser.
            'column' => 'fname',

            // The route used to generate the user link.
            'route' => '/users/profile/@',

            // Notification class to use when this model is mentioned.
            'notification1' => App\Notifications\MentionNotificationdp::class,
            'notification2' => App\Notifications\MentionNotificationdr::class,
            'notification3' => App\Notifications\MentionNotificationwp::class,
            'notification4' => App\Notifications\MentionNotificationwr::class,
        ]
    ]
];
