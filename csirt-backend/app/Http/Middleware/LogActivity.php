<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log for authenticated users
        if (auth()->check() && $this->shouldLog($request)) {
            $this->logActivity($request, $response);
        }

        return $response;
    }

    private function shouldLog(Request $request): bool
    {
        // Don't log these routes/methods
        $excludedPaths = [
            'api/notifications/unread-count',
            'api/dashboard/stats',
            'heartbeat',
            'health-check'
        ];

        $excludedMethods = ['HEAD', 'OPTIONS'];

        if (in_array($request->method(), $excludedMethods)) {
            return false;
        }

        foreach ($excludedPaths as $path) {
            if (str_contains($request->path(), $path)) {
                return false;
            }
        }

        return true;
    }

    private function logActivity(Request $request, Response $response): void
    {
        $action = $this->determineAction($request);
        $description = $this->generateDescription($request, $action);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }

    private function determineAction(Request $request): string
    {
        $method = strtolower($request->method());
        $path = $request->path();

        if (str_contains($path, 'admin/')) {
            if ($method === 'post') {
                return 'created';
            } elseif ($method === 'put' || $method === 'patch') {
                return 'updated';
            } elseif ($method === 'delete') {
                return 'deleted';
            }
        }

        if (str_contains($path, 'login')) {
            return 'login_attempt';
        }

        if (str_contains($path, 'logout')) {
            return 'logout';
        }

        return 'viewed';
    }

    private function generateDescription(Request $request, string $action): string
    {
        $user = auth()->user();
        $path = $request->path();
        $method = strtoupper($request->method());

        $baseDescription = "User {$user->full_name} {$action}";

        // Try to make description more specific based on route
        if (str_contains($path, 'incidents')) {
            return $baseDescription . ' incident data';
        } elseif (str_contains($path, 'news')) {
            return $baseDescription . ' news article';
        } elseif (str_contains($path, 'users')) {
            return $baseDescription . ' user account';
        } elseif (str_contains($path, 'dashboard')) {
            return $baseDescription . ' dashboard';
        } elseif (str_contains($path, 'settings')) {
            return $baseDescription . ' system settings';
        }

        return $baseDescription . " via {$method} {$path}";
    }
}