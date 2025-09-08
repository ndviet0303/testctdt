<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department',
        'permissions',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'permissions' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission($permission)
    {
        // Check direct permissions
        if (is_array($this->permissions) && in_array($permission, $this->permissions)) {
            return true;
        }

        // Check role-based permissions
        $rolePermissions = $this->getRolePermissions();
        
        if (isset($rolePermissions[$this->role]) && in_array($permission, $rolePermissions[$this->role])) {
            return true;
        }

        return false;
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission($permissions)
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user has all of the given permissions
     */
    public function hasAllPermissions($permissions)
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get role-based permissions
     */
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

    /**
     * Get all permissions for this user
     */
    public function getAllPermissions()
    {
        $rolePermissions = $this->getRolePermissions();
        $permissions = $rolePermissions[$this->role] ?? [];
        
        if (is_array($this->permissions)) {
            $permissions = array_merge($permissions, $this->permissions);
        }
        
        return array_unique($permissions);
    }
}
