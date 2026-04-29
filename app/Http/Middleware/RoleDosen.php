<?php
namespace App\Http\Middleware;
 
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
 
class RoleDosen
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->hasRole('dosen')) {
            abort(403, 'Akses ditolak. Halaman ini khusus dosen.');
        }
        return $next($request);
    }
}