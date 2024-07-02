<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \App\Http\Middleware\CorsMiddleware::class,
        \App\Http\Middleware\StaticFilesCors::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            // Otros middlewares
        ],

        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\CorsMiddleware::class,
            \App\Http\Middleware\StaticFilesCors::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        // Otros middlewares
        'cors' => \App\Http\Middleware\CorsMiddleware::class,
    ];

    protected $middlewarePriority = [
        \App\Http\Middleware\CorsMiddleware::class,
        \App\Http\Middleware\StaticFilesCors::class,
    ];
}
