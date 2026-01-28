<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ActivityLog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class UserActivityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check() && $request->route()) {

            $action = $this->detectAction($request->method());
            $table  = $this->detectTableFromController($request);
            $description = $this->makeDescription($action, $table);

            ActivityLog::create([
                'user_id'     => Auth::id(),
                'action'      => $action,
                'description' => $description,
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
            ]);
        }

        return $response;
    }

    private function detectAction(string $method): string
    {
        return match ($method) {
            'POST'         => 'CREATE',
            'PUT', 'PATCH' => 'UPDATE',
            'DELETE'       => 'DELETE',
            default        => 'READ',
        };
    }

    private function detectTableFromController(Request $request): string
    {
        try {
            $controller = class_basename($request->route()->getController());

            // AbsensiController → Absensi
            $resource = str_replace('Controller', '', $controller);

            // absensi
            $snake = Str::snake($resource);

            // kandidat nama tabel (dinamis)
            $candidates = [
                $snake,
                Str::plural($snake),
                'tbl_' . $snake,
                'tbl_' . Str::plural($snake),
            ];

            foreach ($candidates as $table) {
                if (Schema::hasTable($table)) {
                    return $table;
                }
            }

            return 'data sistem';
        } catch (\Throwable $e) {
            return 'data sistem';
        }
    }

    private function makeDescription(string $action, string $table): string
    {
        return match ($action) {
            'CREATE' => "Menambahkan data pada tabel {$table}",
            'UPDATE' => "Mengubah data pada tabel {$table}",
            'DELETE' => "Menghapus data pada tabel {$table}",
            default  => "Melihat data pada tabel {$table}",
        };
    }
}
