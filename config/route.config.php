<?php

namespace Zf2Phinx;

use Zf2Phinx\Controller\PhinxController;

return [
    'test' => [
        'options' => [
            'route' => 'zf2phinx test [-v|-vv|-vvv] [-q] [-n] -e ENVIRONMENT',
            'defaults' => [
                'controller' => PhinxController::class,
                'action'     => 'test'
            ],
        ],
    ],
    'create' => [
        'options' => [
            'route' => 'zf2phinx create [-v|-vv|-vvv] [-q] [-n] [-t] [-l] MIGRATION',
            'defaults' => [
                'controller' => PhinxController::class,
                'action'     => 'create'
            ],
        ],
    ],
    'migrate' => [
        'options' => [
            'route' => 'zf2phinx migrate [-v|-vv|-vvv] [-q] [-n] [-t] [-d] -e ENVIRONMENT',
            'defaults' => [
                'controller' => PhinxController::class,
                'action'     => 'migrate'
            ],
        ],
    ],
    'rollback' => [
        'options' => [
            'route' => 'zf2phinx rollback [-v|-vv|-vvv] [-q] [-n] [-t] [-d] -e ENVIRONMENT',
            'defaults' => [
                'controller' => PhinxController::class,
                'action'     => 'rollback'
            ],
        ],
    ],
    'status' => [
        'options' => [
            'route' => 'zf2phinx status [-v|-vv|-vvv] [-q] [-n] [-f] -e ENVIRONMENT',
            'defaults' => [
                'controller' => PhinxController::class,
                'action'     => 'status'
            ],
        ],
    ],
];
