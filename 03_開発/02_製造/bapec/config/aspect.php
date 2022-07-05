<?php

return [
    'force_compile' => env('ASPECT_FORCE_COMPILE', false),
    'compile_dir' => storage_path('framework/aop/compile'),
    'cache' => env('ASPECT_CACHEABLE', false),
    'cache_dir' => storage_path('framework/aop/cache'),
    'modules' => [
        \App\Aspect\Modules\TransactionalModule::class
    ],
    'annotation' => [
        'ignores' => [
            'Hears',
            'Get',
            'Post',
            'Put',
            'Patch',
            'Options',
            'Delete',
            'Any',
            'Middleware',
            'Resource',
            'Controller'
        ],
        'custom' => [
            \App\Annotation\Transactional::class
        ],
    ],
];
