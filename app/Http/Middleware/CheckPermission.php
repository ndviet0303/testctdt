<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục');
        }

        $user = auth()->user();
        
        // Check if user has the required permission
        if (!$this->hasPermission($user, $permission)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Không có quyền truy cập'], 403);
            }
            
            return redirect()->back()->with('error', 'Bạn không có quyền thực hiện hành động này');
        }

        return $next($request);
    }

    private function hasPermission($user, $permission)
    {
        // Kiểm tra permissions trong user
        if (is_array($user->permissions) && in_array($permission, $user->permissions)) {
            return true;
        }

        // Kiểm tra role-based permissions
        $rolePermissions = $this->getRolePermissions();
        $userRole = $user->role;
        
        if (isset($rolePermissions[$userRole]) && in_array($permission, $rolePermissions[$userRole])) {
            return true;
        }

        return false;
    }

    private function getRolePermissions()
    {
        return [
            'system_admin' => [
                'manage_system', 'configure_workflows', 'view_all_requests', 
                'approve_requests', 'reject_requests', 'create_requests', 
                'view_own_requests', 'manage_users', 'view_requests',
                'create_schools', 'propose_departments'
            ],
            'director' => [
                'approve_final', 'view_all_requests', 'reject_requests', 
                'approve_requests', 'view_reports', 'view_requests'
            ],
            'user_admin' => [
                'manage_users', 'assign_permissions', 'view_requests', 'approve_requests'
            ],
            'auditor' => [
                'audit_logs', 'view_history', 'generate_reports', 'view_all_requests', 'view_requests', 'approve_requests'
            ],
            'reporter' => [
                'generate_reports', 'export_data', 'view_statistics', 'view_requests', 'approve_requests'
            ],
            'school_admin' => [
                'create_schools', 'manage_school_data', 'view_requests', 'create_requests'
            ],
            'policy_admin' => [
                'manage_policies', 'review_regulations', 'approve_changes', 'approve_requests', 'view_requests'
            ],
            'department_proposer' => [
                'propose_departments', 'create_requests', 'view_own_requests'
            ],
            'department_editor' => [
                'edit_departments', 'modify_requests', 'view_requests', 'approve_requests'
            ],
            'change_proposer' => [
                'propose_changes', 'suggest_modifications', 'view_requests', 'approve_requests'
            ],
            'user' => [
                'create_requests', 'view_own_requests'
            ]
        ];
    }
}
