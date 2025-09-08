<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApprovalWorkflowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Quy trình tạo Trường mới
        \App\Models\ApprovalWorkflow::updateOrCreate(
            ['code' => 'CREATE_SCHOOL'],
            [
            'name' => 'Tạo Trường mới',
            'code' => 'CREATE_SCHOOL',
            'description' => 'Quy trình phê duyệt tạo Trường mới trong hệ thống',
            'entity_type' => 'school',
            'action_type' => 'create',
            'is_active' => true,
            'workflow_steps' => [
                [
                    'order' => 1,
                    'name' => 'Cấu hình hệ thống',
                    'description' => 'Thiết lập cấu hình ban đầu cho Trường mới',
                    'approver_role' => 'system_admin',
                    'is_required' => true,
                    'can_delegate' => false,
                    'timeout_hours' => 24,
                ],
                [
                    'order' => 2,
                    'name' => 'Quản lý người dùng',
                    'description' => 'Thiết lập quyền và người dùng cho Trường',
                    'approver_role' => 'user_admin',
                    'is_required' => true,
                    'can_delegate' => true,
                    'timeout_hours' => 48,
                ],
                [
                    'order' => 3,
                    'name' => 'Xem lịch sử & audit',
                    'description' => 'Kiểm tra và xác nhận lịch sử thay đổi',
                    'approver_role' => 'auditor',
                    'is_required' => true,
                    'can_delegate' => false,
                    'timeout_hours' => 24,
                ],
                [
                    'order' => 4,
                    'name' => 'Phê duyệt thay đổi',
                    'description' => 'Phê duyệt cuối cùng từ lãnh đạo',
                    'approver_role' => 'director',
                    'is_required' => true,
                    'can_delegate' => false,
                    'timeout_hours' => 72,
                ],
                [
                    'order' => 5,
                    'name' => 'Xuất báo cáo',
                    'description' => 'Tạo báo cáo hoàn thành việc tạo Trường',
                    'approver_role' => 'reporter',
                    'is_required' => false,
                    'can_delegate' => true,
                    'timeout_hours' => 24,
                ]
            ]
            ]
        );

        // Quy trình thiết lập hiệu lực Trường
        \App\Models\ApprovalWorkflow::updateOrCreate(
            ['code' => 'ACTIVATE_SCHOOL'],
            [
            'name' => 'Thiết lập hiệu lực Trường',
            'code' => 'ACTIVATE_SCHOOL',
            'description' => 'Quy trình phê duyệt kích hoạt Trường',
            'entity_type' => 'school',
            'action_type' => 'activate',
            'is_active' => true,
            'workflow_steps' => [
                [
                    'order' => 1,
                    'name' => 'Tạo Trường',
                    'description' => 'Tạo bản ghi Trường trong hệ thống',
                    'approver_role' => 'school_admin',
                    'is_required' => true,
                    'can_delegate' => false,
                    'timeout_hours' => 24,
                ],
                [
                    'order' => 2,
                    'name' => 'Thiết lập hiệu lực Trường',
                    'description' => 'Kích hoạt trạng thái hoạt động của Trường',
                    'approver_role' => 'system_admin',
                    'is_required' => true,
                    'can_delegate' => false,
                    'timeout_hours' => 48,
                ],
                [
                    'order' => 3,
                    'name' => 'Phê duyệt thay đổi',
                    'description' => 'Phê duyệt kích hoạt từ lãnh đạo',
                    'approver_role' => 'director',
                    'is_required' => true,
                    'can_delegate' => false,
                    'timeout_hours' => 72,
                ],
                [
                    'order' => 4,
                    'name' => 'Gián Nghiêm vào Trường',
                    'description' => 'Thiết lập quy định và chính sách cho Trường',
                    'approver_role' => 'policy_admin',
                    'is_required' => true,
                    'can_delegate' => true,
                    'timeout_hours' => 48,
                ],
                [
                    'order' => 5,
                    'name' => 'Quản lý người dùng',
                    'description' => 'Cấp quyền truy cập cho người dùng Trường',
                    'approver_role' => 'user_admin',
                    'is_required' => true,
                    'can_delegate' => true,
                    'timeout_hours' => 24,
                ],
                [
                    'order' => 6,
                    'name' => 'Xem lịch sử & audit',
                    'description' => 'Kiểm tra lịch sử kích hoạt',
                    'approver_role' => 'auditor',
                    'is_required' => true,
                    'can_delegate' => false,
                    'timeout_hours' => 24,
                ],
                [
                    'order' => 7,
                    'name' => 'Xuất báo cáo',
                    'description' => 'Tạo báo cáo kích hoạt Trường',
                    'approver_role' => 'reporter',
                    'is_required' => false,
                    'can_delegate' => true,
                    'timeout_hours' => 24,
                ]
            ]
            ]
        );

        // Quy trình tạo Khoa mới
        \App\Models\ApprovalWorkflow::updateOrCreate(
            ['code' => 'CREATE_DEPARTMENT'],
            [
            'name' => 'Tạo Khoa mới',
            'code' => 'CREATE_DEPARTMENT',
            'description' => 'Quy trình phê duyệt tạo Khoa mới trong Trường',
            'entity_type' => 'department',
            'action_type' => 'create',
            'is_active' => true,
            'workflow_steps' => [
                [
                    'order' => 1,
                    'name' => 'Đề xuất thêm Khoa',
                    'description' => 'Tạo đề xuất thêm Khoa mới',
                    'approver_role' => 'department_proposer',
                    'is_required' => true,
                    'can_delegate' => false,
                    'timeout_hours' => 48,
                ],
                [
                    'order' => 2,
                    'name' => 'Sửa Khoa',
                    'description' => 'Chỉnh sửa thông tin Khoa theo yêu cầu',
                    'approver_role' => 'department_editor',
                    'is_required' => false,
                    'can_delegate' => true,
                    'timeout_hours' => 24,
                ],
                [
                    'order' => 3,
                    'name' => 'Gán Nghiêm vào Khoa',
                    'description' => 'Thiết lập quy định cho Khoa mới',
                    'approver_role' => 'policy_admin',
                    'is_required' => true,
                    'can_delegate' => true,
                    'timeout_hours' => 48,
                ],
                [
                    'order' => 4,
                    'name' => 'Đề xuất thay đổi',
                    'description' => 'Đề xuất các thay đổi cần thiết',
                    'approver_role' => 'change_proposer',
                    'is_required' => false,
                    'can_delegate' => true,
                    'timeout_hours' => 24,
                ],
                [
                    'order' => 5,
                    'name' => 'Quản lý người dùng',
                    'description' => 'Cấp quyền cho người dùng Khoa',
                    'approver_role' => 'user_admin',
                    'is_required' => true,
                    'can_delegate' => true,
                    'timeout_hours' => 24,
                ],
                [
                    'order' => 6,
                    'name' => 'Xem lịch sử & audit',
                    'description' => 'Kiểm tra lịch sử tạo Khoa',
                    'approver_role' => 'auditor',
                    'is_required' => true,
                    'can_delegate' => false,
                    'timeout_hours' => 24,
                ],
                [
                    'order' => 7,
                    'name' => 'Xuất báo cáo',
                    'description' => 'Tạo báo cáo hoàn thành tạo Khoa',
                    'approver_role' => 'reporter',
                    'is_required' => false,
                    'can_delegate' => true,
                    'timeout_hours' => 24,
                ]
            ]
            ]
        );
    }
}
