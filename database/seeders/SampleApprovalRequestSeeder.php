<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ApprovalWorkflow;
use App\Models\ApprovalRequest;
use App\Models\ApprovalStep;
use App\Models\User;

class SampleApprovalRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy workflows và users
        $createSchoolWorkflow = ApprovalWorkflow::where('code', 'CREATE_SCHOOL')->first();
        $users = User::all()->keyBy('role');

        // Tạo yêu cầu mẫu
        if ($createSchoolWorkflow && isset($users['user'])) {
            $request = ApprovalRequest::updateOrCreate(
                ['request_code' => 'CREATE_SCHOOL-20250908-0001'], // Tìm theo request_code
                [
                'workflow_id' => $createSchoolWorkflow->id,
                'request_code' => 'CREATE_SCHOOL-20250908-0001',
                'title' => 'Tạo Trường Khoa học Ứng dụng',
                'description' => 'Yêu cầu tạo Trường Khoa học Ứng dụng mới',
                'requester_id' => $users['user']->id,
                'requester_name' => $users['user']->name,
                'requester_email' => $users['user']->email,
                'requester_department' => $users['user']->department,
                'entity_type' => 'school',
                'entity_data' => [
                    'university_id' => 1,
                    'name' => 'Khoa học Ứng dụng',
                    'code' => 'KHUD',
                    'full_name' => 'Trường Khoa học Ứng dụng',
                    'description' => 'Trường chuyên về nghiên cứu khoa học',
                    'dean_name' => 'PGS.TS Nguyễn Văn Khoa học',
                    'phone' => '024-6291-8888',
                    'email' => 'khud@phenikaa.edu.vn',
                    'is_active' => false
                ],
                'status' => 'submitted',
                'current_step' => 1,
                'submitted_at' => now()->subDays(2),
                'approval_history' => [
                    [
                        'action' => 'submitted',
                        'message' => 'Yêu cầu được gửi để phê duyệt',
                        'timestamp' => now()->subDays(2)->toISOString(),
                        'user_id' => $users['user']->id
                    ]
                ]
                ]
            );

            // Tạo steps
            $this->createStepsForRequest($request, $createSchoolWorkflow, $users);
        }
    }

    private function createStepsForRequest($request, $workflow, $users, $currentStep = 1)
    {
        foreach ($workflow->workflow_steps as $stepConfig) {
            $stepOrder = $stepConfig['order'];
            $approverRole = $stepConfig['approver_role'];
            $status = $stepOrder == $currentStep ? 'pending' : 'pending';
            $approver = isset($users[$approverRole]) ? $users[$approverRole] : null;

            $stepData = [
                'request_id' => $request->id,
                'step_order' => $stepOrder,
                'step_name' => $stepConfig['name'],
                'step_description' => $stepConfig['description'],
                'approver_role' => $approverRole,
                'status' => $status,
                'is_required' => $stepConfig['is_required'] ?? true,
                'can_delegate' => $stepConfig['can_delegate'] ?? false,
                'timeout_hours' => $stepConfig['timeout_hours'] ?? null,
            ];

            if ($approver) {
                $stepData['approver_id'] = $approver->id;
                $stepData['approver_name'] = $approver->name;
                $stepData['approver_department'] = $approver->department;
            }

            if ($stepOrder === $currentStep) {
                $stepData['assigned_at'] = now()->subHours(2);
            }

            ApprovalStep::updateOrCreate(
                [
                    'request_id' => $request->id,
                    'step_order' => $stepOrder
                ],
                $stepData
            );
        }
    }
}
