<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ActivityLog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
    // public function handle(Request $request, Closure $next)
    // {
    //     $routeName = $request->route()?->getName();
    //     $path      = $request->path();

    //     /* ==========================
    //      | LOGOUT (SEBELUM LOGOUT)
    //      ========================== */
    //     if (Auth::check() && (
    //         $routeName === 'logout' || Str::contains($path, 'logout')
    //     )) {
    //         ActivityLog::create([
    //             'user_id'     => Auth::id(),
    //             'action'      => 'AUTH',
    //             'description' => 'User melakukan logout dari sistem',
    //             'ip_address'  => $request->ip(),
    //             'user_agent'  => $request->userAgent(),
    //         ]);
    //     }

    //     $response = $next($request);

    //     /* ==========================
    //      | LOGIN & REGISTER
    //      ========================== */
    //     if (
    //         Auth::check() &&
    //         (
    //             $routeName === 'login' ||
    //             $routeName === 'register' ||
    //             Str::contains($path, 'login') ||
    //             Str::contains($path, 'register')
    //         )
    //     ) {
    //         ActivityLog::create([
    //             'user_id'     => Auth::id(),
    //             'action'      => 'AUTH',
    //             'description' => $routeName === 'register'
    //                 ? 'User melakukan registrasi akun'
    //                 : 'User melakukan login ke sistem',
    //             'ip_address'  => $request->ip(),
    //             'user_agent'  => $request->userAgent(),
    //         ]);

    //         return $response;
    //     }

    //     /* ==========================
    //      | CRUD ACTIVITY
    //      ========================== */
    //     if (Auth::check() && $request->route()) {

    //         $action      = $this->detectAction($request->method());
    //         $table       = $this->detectTableFromController($request);
    //         $description = $this->makeDescription($action, $table);

    //         ActivityLog::create([
    //             'user_id'     => Auth::id(),
    //             'action'      => $action,
    //             'description' => $description,
    //             'ip_address'  => $request->ip(),
    //             'user_agent'  => $request->userAgent(),
    //         ]);
    //     }

    //     return $response;
    // }

    // private function detectAction(string $method): string
    // {
    //     return match ($method) {
    //         'POST'         => 'CREATE',
    //         'PUT', 'PATCH' => 'UPDATE',
    //         'DELETE'       => 'DELETE',
    //         default        => 'READ',
    //     };
    // }

    // private function detectTableFromController(Request $request): string
    // {
    //     try {
    //         $controller = class_basename($request->route()->getController());
    //         $resource   = str_replace('Controller', '', $controller);
    //         $snake      = Str::snake($resource);

    //         $candidates = [
    //             $snake,
    //             Str::plural($snake),
    //             'tbl_' . $snake,
    //             'tbl_' . Str::plural($snake),
    //         ];

    //         foreach ($candidates as $table) {
    //             if (Schema::hasTable($table)) {
    //                 return $table;
    //             }
    //         }

    //         return 'data sistem';
    //     } catch (\Throwable $e) {
    //         return 'data sistem';
    //     }
    // }

    // private function makeDescription(string $action, string $table): string
    // {
    //     return match ($action) {
    //         'CREATE' => "Menambahkan data pada tabel {$table}",
    //         'UPDATE' => "Mengubah data pada tabel {$table}",
    //         'DELETE' => "Menghapus data pada tabel {$table}",
    //         default  => "Melihat data pada tabel {$table}",
    //     };
    // }

    private array $excludedPaths = [
        'api/sanctum',
        'livewire',
        '_debugbar',
        'horizon',
        'telescope',
        'health-check',
        'ping',
    ];

    /**
     * HTTP methods yang tidak perlu dicatat untuk READ
     */
    private array $ignoredReadMethods = ['HEAD', 'OPTIONS'];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()?->getName();
        $path = $request->path();

        /* ==========================
         | LOGOUT (SEBELUM LOGOUT)
         ========================== */
        if (Auth::check() && $this->isLogoutRoute($routeName, $path)) {
            $this->logActivity($request, [
                'action' => 'AUTH',
                'description' => 'User melakukan logout dari sistem',
            ]);
        }

        // Proses request
        $response = $next($request);

        // Skip jika path dikecualikan
        if ($this->shouldSkip($request)) {
            return $response;
        }

        // Hanya log jika response sukses (2xx atau redirect)
        if (!$response->isSuccessful() && !$response->isRedirection()) {
            return $response;
        }

        // Harus ada user yang login untuk log selanjutnya
        if (!Auth::check()) {
            return $response;
        }

        /* ==========================
         | LOGIN & REGISTER (SETELAH BERHASIL)
         ========================== */
        if ($this->isAuthRoute($routeName, $path)) {
            $isRegister = Str::contains($routeName ?? $path, 'register');
            
            $this->logActivity($request, [
                'action' => 'AUTH',
                'description' => $isRegister 
                    ? 'User melakukan registrasi akun'
                    : 'User melakukan login ke sistem',
            ]);

            return $response;
        }

        /* ==========================
         | CRUD ACTIVITY
         ========================== */
        if ($this->shouldLogCrud($request)) {
            $action = $this->detectAction($request->method());
            $context = $this->extractContext($request, $action);

            $this->logActivity($request, [
                'action' => $action,
                'description' => $context['description'],
                'table_name' => $context['table_name'] ?? null,
                'record_id' => $context['record_id'] ?? null,
            ]);
        }

        return $response;
    }

    /**
     * Cek apakah request harus di-skip
     */
    private function shouldSkip(Request $request): bool
    {
        $path = $request->path();

        // Skip asset files
        if (preg_match('/\.(css|js|jpg|jpeg|png|gif|svg|woff|woff2|ttf|eot|ico|map)$/i', $path)) {
            return true;
        }

        // Skip excluded paths
        foreach ($this->excludedPaths as $excluded) {
            if (Str::startsWith($path, $excluded)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Cek apakah ini route logout
     */
    private function isLogoutRoute(?string $routeName, string $path): bool
    {
        // Check route name
        if ($routeName === 'logout') {
            return true;
        }

        // Check path
        $logoutPatterns = ['logout', 'log-out', 'signout', 'sign-out'];
        
        foreach ($logoutPatterns as $pattern) {
            if (Str::endsWith($path, $pattern) || Str::contains($path, "/{$pattern}")) {
                return true;
            }
        }

        return false;
    }

    /**
     * Cek apakah ini route auth
     */
    private function isAuthRoute(?string $routeName, string $path): bool
    {
        // Pastikan ini POST request (submit login/register)
        if (request()->method() !== 'POST') {
            return false;
        }

        $authKeywords = ['login', 'signin', 'sign-in', 'register', 'signup', 'sign-up'];
        
        foreach ($authKeywords as $keyword) {
            if (Str::contains($routeName ?? '', $keyword) || 
                Str::contains($path, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Cek apakah CRUD perlu di-log
     */
    private function shouldLogCrud(Request $request): bool
    {
        $method = $request->method();

        // Ignore methods yang tidak penting
        if (in_array($method, $this->ignoredReadMethods)) {
            return false;
        }

        // Untuk READ (GET), hanya log jika ada route name dan bukan asset
        if ($method === 'GET') {
            $routeName = $request->route()?->getName();
            
            // Hanya log GET untuk route tertentu (index, show, edit, dll)
            if (!$routeName) {
                return false;
            }

            // Skip GET untuk dashboard, home, profile (terlalu banyak noise)
            $skipGetRoutes = ['dashboard', 'home', 'profile.show'];
            if (in_array($routeName, $skipGetRoutes)) {
                return false;
            }

            // Log GET hanya untuk route yang jelas (index, show, edit, create, export, dll)
            $logGetPatterns = [
                'index', 'show', 'edit', 'create', 'export', 
                'download', 'print', 'detail', 'view'
            ];

            foreach ($logGetPatterns as $pattern) {
                if (Str::contains($routeName, $pattern)) {
                    return true;
                }
            }

            return false;
        }

        // POST, PUT, PATCH, DELETE selalu di-log
        return in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE']);
    }

    /**
     * Deteksi action dari HTTP method
     */
    private function detectAction(string $method): string
    {
        return match ($method) {
            'POST' => 'CREATE',
            'PUT', 'PATCH' => 'UPDATE',
            'DELETE' => 'DELETE',
            default => 'READ',
        };
    }

    /**
     * Extract context dari request (table, record_id, description)
     */
    private function extractContext(Request $request, string $action): array
    {
        $routeName = $request->route()?->getName();
        $controller = $this->getControllerName($request);
        $tableName = $this->detectTableName($controller, $request);
        $recordId = $this->extractRecordId($request, $action);
        
        // Buat description yang lebih spesifik
        $description = $this->makeDetailedDescription(
            $action, 
            $tableName, 
            $recordId, 
            $routeName,
            $request
        );

        return [
            'table_name' => $tableName !== 'data sistem' ? $tableName : null,
            'record_id' => $recordId,
            'description' => $description,
        ];
    }

    /**
     * Get controller name
     */
    private function getControllerName(Request $request): ?string
    {
        try {
            $controller = $request->route()?->getController();
            if (!$controller) {
                return null;
            }
            return class_basename($controller);
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Deteksi nama tabel dengan lebih akurat
     */
    private function detectTableName(?string $controller, Request $request): string
    {
        if (!$controller) {
            return 'data sistem';
        }

        try {
            // Ambil resource name dari controller
            $resource = str_replace('Controller', '', $controller);
            $snake = Str::snake($resource);

            // Kandidat nama tabel (urutan prioritas)
            $candidates = [
                Str::plural($snake),           // users, products
                $snake,                        // user, product
                'tbl_' . Str::plural($snake),  // tbl_users
                'tbl_' . $snake,               // tbl_user
                'tb_' . Str::plural($snake),   // tb_users
                'tb_' . $snake,                // tb_user
            ];

            // Cek di database
            foreach ($candidates as $table) {
                if (Schema::hasTable($table)) {
                    return $table;
                }
            }

            // Fallback: gunakan nama resource (human readable)
            return Str::plural(Str::lower(Str::snake($resource, ' ')));

        } catch (\Throwable $e) {
            return 'data sistem';
        }
    }

    /**
     * Extract record ID dari request
     */
    private function extractRecordId(Request $request, string $action): ?int
    {
        // Untuk CREATE, belum ada ID
        if ($action === 'CREATE') {
            return null;
        }

        // Coba ambil dari route parameters
        $routeParams = $request->route()?->parameters() ?? [];
        
        // Common parameter names untuk ID
        $idKeys = ['id', 'user', 'product', 'item', 'record'];
        
        foreach ($idKeys as $key) {
            if (isset($routeParams[$key])) {
                $value = $routeParams[$key];
                
                // Jika object model, ambil ID-nya
                if (is_object($value) && method_exists($value, 'getKey')) {
                    return $value->getKey();
                }
                
                // Jika integer
                if (is_numeric($value)) {
                    return (int) $value;
                }
            }
        }

        // Coba ambil dari input untuk UPDATE/DELETE
        if (in_array($action, ['UPDATE', 'DELETE'])) {
            if ($request->has('id')) {
                return (int) $request->input('id');
            }
        }

        return null;
    }

    /**
     * Buat description yang lebih detail dan spesifik
     */
    private function makeDetailedDescription(
        string $action, 
        string $tableName, 
        ?int $recordId,
        ?string $routeName,
        Request $request
    ): string {
        $recordInfo = $recordId ? " (ID: {$recordId})" : '';
        $tableDisplay = $tableName !== 'data sistem' ? $tableName : 'data sistem';

        // Tambahkan context dari route name jika ada
        $context = $this->getContextFromRoute($routeName);

        return match ($action) {
            'CREATE' => "Menambahkan data baru pada {$tableDisplay}{$context}",
            'UPDATE' => "Mengubah data pada {$tableDisplay}{$recordInfo}{$context}",
            'DELETE' => "Menghapus data pada {$tableDisplay}{$recordInfo}{$context}",
            'READ' => $this->makeReadDescription($tableDisplay, $recordId, $routeName, $context),
        };
    }

    /**
     * Buat description untuk READ yang lebih spesifik
     */
    private function makeReadDescription(
        string $tableName, 
        ?int $recordId, 
        ?string $routeName,
        string $context
    ): string {
        $recordInfo = $recordId ? " (ID: {$recordId})" : '';

        // Deteksi jenis READ dari route name
        if ($routeName) {
            if (Str::contains($routeName, 'export')) {
                return "Mengekspor data dari {$tableName}{$context}";
            }
            if (Str::contains($routeName, 'download')) {
                return "Mengunduh data dari {$tableName}{$recordInfo}{$context}";
            }
            if (Str::contains($routeName, 'print')) {
                return "Mencetak data dari {$tableName}{$recordInfo}{$context}";
            }
            if (Str::contains($routeName, ['show', 'detail', 'view'])) {
                return "Melihat detail data pada {$tableName}{$recordInfo}{$context}";
            }
            if (Str::contains($routeName, ['edit', 'form'])) {
                return "Membuka form edit pada {$tableName}{$recordInfo}{$context}";
            }
            if (Str::contains($routeName, ['create', 'add'])) {
                return "Membuka form tambah data pada {$tableName}{$context}";
            }
            if (Str::contains($routeName, 'index')) {
                return "Melihat daftar data pada {$tableName}{$context}";
            }
        }

        return "Mengakses data pada {$tableName}{$recordInfo}{$context}";
    }

    /**
     * Get context dari route name
     */
    private function getContextFromRoute(?string $routeName): string
    {
        if (!$routeName) {
            return '';
        }

        // Extract module/prefix dari route name
        // Contoh: admin.users.index -> admin
        $parts = explode('.', $routeName);
        if (count($parts) > 2) {
            $prefix = $parts[0];
            if (!in_array($prefix, ['index', 'show', 'create', 'edit', 'store', 'update', 'destroy'])) {
                return " [{$prefix}]";
            }
        }

        return '';
    }

    /**
     * Log activity ke database
     */
    private function logActivity(Request $request, array $data): void
    {
        try {
            ActivityLog::create(array_merge([
                'user_id' => Auth::id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ], $data));
        } catch (\Throwable $e) {
            // Silent fail - jangan sampai logging error mengganggu aplikasi
            Log::error('Failed to log activity: ' . $e->getMessage());
        }
    }
}
