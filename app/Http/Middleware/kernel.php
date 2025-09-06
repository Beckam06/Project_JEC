protected $routeMiddleware = [
    // ... otros middlewares
    'client.auth' => \App\Http\Middleware\ClientAuth::class,
];