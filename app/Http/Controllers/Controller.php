<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\ProductRequest;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        // Compartir el conteo de solicitudes pendientes con todas las vistas
        view()->share('pendingRequestsCount', ProductRequest::where('status', 'pendiente')->count());
    }
}