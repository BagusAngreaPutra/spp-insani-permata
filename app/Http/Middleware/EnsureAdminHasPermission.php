<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminHasPermission
{
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $admin = Auth::guard('web')->user();

        if (! $admin) {
            return redirect()->route('login');
        }

        if ($admin->isSuperAdmin()) {
            return $next($request);
        }

        $permissions = collect($permissions)
            ->flatMap(fn (string $permission) => explode(',', $permission))
            ->map(fn (string $permission) => trim($permission))
            ->filter()
            ->values()
            ->all();

        if ($admin->hasAnyPermission($permissions)) {
            return $next($request);
        }

        abort(403, 'Anda tidak memiliki hak akses untuk membuka fitur ini.');
    }
}
