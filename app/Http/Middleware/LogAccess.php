<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AccessLog;

class LogAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Don't log Livewire internal requests to avoid spam
        if ($request->route() && str_starts_with($request->route()->uri(), 'livewire/')) {
            return $next($request);
        }

        // Run the request first to ensure it's successful
        $response = $next($request);

        // We only log if it's an authenticated user in the admin area or successful pages
        if (auth()->check()) {
            AccessLog::create([
                'user_id' => auth()->id(),
                'method' => $request->method(),
                'status_code' => $response->getStatusCode(),
                'url' => $request->fullUrl(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'error_text' => isset($response->exception) ? mb_substr($response->exception->getMessage(), 0, 1000) : null,
            ]);
        }

        return $response;
    }
}
