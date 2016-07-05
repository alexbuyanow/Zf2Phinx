<?php

namespace Zf2Phinx;

use Zf2Phinx\Controller\PhinxController;
use Zf2Phinx\Controller\PhinxControllerFactory;
use Zf2Phinx\Service\Zf2PhinxService;
use Zf2Phinx\Service\Zf2PhinxServiceFactory;

return [
    'console' => [
        'router' => [
            'routes' => require __DIR__.'/route.config.php',
        ],
    ],

    'controllers' => [
        'factories' => [
            PhinxController::class => PhinxControllerFactory::class,
        ],
    ],

    'service_manager' => [
        'factories' => [
            Zf2PhinxService::class => Zf2PhinxServiceFactory::class,
        ],
    ],

    'zf2phinx' => [
        'paths' => [
            'migrations' => '',
            'seeds'      => '',
        ],

        'environments' => [
            'default_migration_table' => 'phinxlog',
            'default_database'        => null,
        ],
    ],
];
