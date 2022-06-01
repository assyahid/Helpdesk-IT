<?php

return [
    'roles' => [
        [
            'extends' => 'users',
            'name' => 'customers',
            'permissions' => [
                'articles.view',
                'tickets.create',
                'files.create',
            ],
        ],
        [
            'extends' => 'guests',
            'name' => 'guests',
            'permissions' => ['articles.view'],
        ],
        [
            'extends' => 'customers',
            'name' => 'agents',
            'default' => false,
            'guests' => false,
            'permissions' => [
                'files.view',
                'tickets.view',
                'tickets.update',
                'tickets.create',
                'tickets.delete',
                'users.view',
                'access.admin',
                'canned_replies.view',
                'canned_replies.create',
                'triggers.view',
                'triggers.create',
                'users.view',
                'localizations.view',
                'custom_pages.view',
                'files.create',
                'plans.view',
                'articles.view',
                'notifications.subscribe',
            ],
        ],
    ],
    'all' => [
        'articles' => [
            [
                'name' => 'articles.view',
                'description' =>
                    'Allow viewing of all help center articles and categories.',
                'advanced' => false,
            ],
            [
                'name' => 'articles.create',
                'advanced' => false,
                'description' =>
                    'Allow creating new articles and categories from help center manager page.',
            ],
            [
                'name' => 'articles.update',
                'description' =>
                    'Allow editing of all articles and categories in help center manager page.',
                'advanced' => false,
            ],
            [
                'name' => 'articles.delete',
                'description' =>
                    'Allow deletion of all articles and categories in help center manager page.',
                'advanced' => false,
            ],
        ],

        'tickets' => [
            [
                'name' => 'tickets.view',
                'description' =>
                    'Allow viewing of all tickets on the site, regardless of who created them. Customers can view their own tickets without this permission. Mainly intended for agents.',
                'advanced' => true,
            ],
            [
                'name' => 'tickets.create',
                'description' =>
                    'Allow logged in customers to create new tickets via the site.',
                'advanced' => false,
            ],
            [
                'name' => 'tickets.update',
                'description' =>
                    'Allow updating of all tickets on the site, regardless of who created them. User with this permission will be considered an agent on BeDesk.',
                'advanced' => true,
            ],
            [
                'name' => 'tickets.delete',
                'description' =>
                    'Allow deleting of all tickets on the site, regardless of who created them. Mainly intended for agents.',
                'advanced' => true,
            ],
        ],

        'canned_replies' => [
            [
                'name' => 'canned_replies.view',
                'description' =>
                    'Allow viewing of all canned replies on the site, regardless of who created them or if they are marked as shared. Users can view their own canned replies and ones marked as "shared" without this permission.',
                'advanced' => true,
            ],
            [
                'name' => 'canned_replies.create',
                'description' =>
                    'Allow agents to create new canned replies from conversation page.',
                'advanced' => false,
            ],
            [
                'name' => 'canned_replies.update',
                'description' =>
                    'Allow updating of all canned replies on the site, regardless of who created them. Agent can update their own canned replies without this permission.',
                'advanced' => true,
            ],
            [
                'name' => 'canned_replies.delete',
                'description' =>
                    'Allow deleting of all canned replies on the site, regardless of who created them. Agent can delete their own canned replies without this permission.',
                'advanced' => true,
            ],
        ],

        'triggers' => [
            [
                'name' => 'triggers.view',
                'description' => 'Allow viewing of all triggers in admin area.',
                'advanced' => true,
            ],
            [
                'name' => 'triggers.create',
                'advanced' => false,
                'description' => 'Allow creating new triggers in admin area.',
            ],
            [
                'name' => 'triggers.update',
                'description' => 'Allow editing of all triggers in admin area.',
                'advanced' => true,
            ],
            [
                'name' => 'triggers.delete',
                'description' =>
                    'Allow deleting of all triggers in admin area.',
                'advanced' => true,
            ],
        ],

        'notifications' => [
            [
                'name' => 'notifications.subscribe',
                'description' =>
                    'Allows agents to subscribe to various conversation notifications.',
            ],
        ],
    ],
];
