<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo các user với role khác nhau theo workflow
        $users = [
            [
                'name' => 'Admin Hệ thống',
                'email' => 'admin@phenikaa.edu.vn',
                'password' => \Hash::make('password'),
                'role' => 'system_admin',
                'department' => 'Phòng CNTT',
                'permissions' => ['manage_system', 'configure_workflows', 'view_all_requests'],
                'is_active' => true
            ],
            [
                'name' => 'Nguyễn Văn Giám đốc',
                'email' => 'director@phenikaa.edu.vn', 
                'password' => \Hash::make('password'),
                'role' => 'director',
                'department' => 'Ban Giám đốc',
                'permissions' => ['approve_final', 'view_all_requests', 'reject_requests'],
                'is_active' => true
            ],
            [
                'name' => 'Trần Thị Quản lý User xử lý',
                'email' => 'useradmin@phenikaa.edu.vn',
                'password' => \Hash::make('password'),
                'role' => 'user_admin',
                'department' => 'Phòng Nhân sự',
                'permissions' => ['manage_users', 'assign_permissions', 'view_requests'],
                'is_active' => true
            ],
            [
                'name' => 'Lê Văn Kiểm toán xử lý',
                'email' => 'auditor@phenikaa.edu.vn',
                'password' => \Hash::make('password'),
                'role' => 'auditor',
                'department' => 'Phòng Kiểm toán',
                'permissions' => ['audit_logs', 'view_history', 'generate_reports', 'view_all_requests'],
                'is_active' => true
            ],
            [
                'name' => 'Phạm Thị Báo cáo xử lý',
                'email' => 'reporter@phenikaa.edu.vn',
                'password' => \Hash::make('password'),
                'role' => 'reporter',
                'department' => 'Phòng Kế hoạch',
                'permissions' => ['generate_reports', 'export_data', 'view_statistics'],
                'is_active' => true
            ],
            [
                'name' => 'Hoàng Văn Quản lý Trường',
                'email' => 'schooladmin@phenikaa.edu.vn',
                'password' => \Hash::make('password'),
                'role' => 'school_admin',
                'department' => 'Phòng Đào tạo',
                'permissions' => ['create_schools', 'manage_school_data', 'view_requests'],
                'is_active' => true
            ],
            [
                'name' => 'Đỗ Văn Đề xuất Khoa',
                'email' => 'deptproposer@phenikaa.edu.vn',
                'password' => \Hash::make('password'),
                'role' => 'department_proposer',
                'department' => 'Phòng Đào tạo',
                'permissions' => ['propose_departments', 'create_requests', 'view_own_requests'],
                'is_active' => true
            ],
            [
                'name' => 'Người dùng thường',
                'email' => 'user@phenikaa.edu.vn',
                'password' => \Hash::make('password'),
                'role' => 'user',
                'department' => 'Khoa CNTT',
                'permissions' => ['create_requests', 'view_own_requests'],
                'is_active' => true
            ]
        ];

        foreach ($users as $userData) {
            \App\Models\User::updateOrCreate(
                ['email' => $userData['email']], // Tìm theo email
                $userData // Update hoặc create với data này
            );
        }
    }
}
