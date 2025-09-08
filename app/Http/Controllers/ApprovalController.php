<?php

namespace App\Http\Controllers;

use App\Models\ApprovalWorkflow;
use App\Models\ApprovalRequest;
use App\Models\ApprovalStep;
use App\Models\University;
use App\Models\School;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApprovalController extends Controller
{
    // Dashboard chính cho quản lý phê duyệt
    public function index()
    {
        $stats = [
            'pending_requests' => ApprovalRequest::whereIn('status', ['submitted', 'in_review'])->count(),
            'approved_today' => ApprovalRequest::where('status', 'approved')
                ->whereDate('approved_at', today())->count(),
            'rejected_today' => ApprovalRequest::where('status', 'rejected')
                ->whereDate('rejected_at', today())->count(),
            'total_workflows' => ApprovalWorkflow::active()->count(),
        ];

        $pendingRequests = ApprovalRequest::with(['workflow', 'steps'])
            ->whereIn('status', ['submitted', 'in_review'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $myTasks = ApprovalStep::where('status', 'pending')
            ->where('approver_id', auth()->id() ?? 1) // Default to user 1 for testing
            ->with(['request.workflow'])
            ->orderBy('assigned_at', 'asc')
            ->take(5)
            ->get();

        return view('approval.index', compact('stats', 'pendingRequests', 'myTasks'));
    }

    // Danh sách tất cả yêu cầu
    public function requests(Request $request)
    {
        $query = ApprovalRequest::with(['workflow', 'steps']);

        // Lọc theo trạng thái
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Lọc theo loại workflow
        if ($request->has('workflow_id') && $request->workflow_id !== '') {
            $query->where('workflow_id', $request->workflow_id);
        }

        // Tìm kiếm
        if ($request->has('search') && $request->search !== '') {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('request_code', 'like', '%' . $request->search . '%')
                  ->orWhere('requester_name', 'like', '%' . $request->search . '%');
            });
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(15);
        $workflows = ApprovalWorkflow::active()->get();

        return view('approval.requests', compact('requests', 'workflows'));
    }

    // Chi tiết yêu cầu
    public function show(ApprovalRequest $request)
    {
        $request->load(['workflow', 'steps']);
        return view('approval.show', compact('request'));
    }

    // Form tạo yêu cầu mới
    public function create()
    {
        $workflows = ApprovalWorkflow::active()->get();
        $universities = University::active()->get();
        return view('approval.create', compact('workflows', 'universities'));
    }

    // Lưu yêu cầu mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'workflow_id' => 'required|exists:approval_workflows,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'entity_data' => 'required|array',
            'requester_name' => 'required|string|max:255',
            'requester_email' => 'required|email',
            'requester_department' => 'nullable|string|max:255',
        ]);

        $workflow = ApprovalWorkflow::findOrFail($validated['workflow_id']);

        DB::beginTransaction();
        try {
            $approvalRequest = $workflow->createRequest([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'entity_type' => $workflow->entity_type,
                'entity_data' => $validated['entity_data'],
                'requester_id' => 1, // Mock user ID
                'requester_name' => $validated['requester_name'],
                'requester_email' => $validated['requester_email'],
                'requester_department' => $validated['requester_department'],
                'status' => 'draft',
            ]);

            DB::commit();

            return redirect()->route('approval.show', $approvalRequest)
                ->with('success', 'Yêu cầu đã được tạo thành công!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // Submit yêu cầu để bắt đầu quy trình phê duyệt
    public function submit(ApprovalRequest $request)
    {
        try {
            $request->submit();
            return redirect()->route('approval.show', $request)
                ->with('success', 'Yêu cầu đã được gửi để phê duyệt!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // Danh sách công việc cần phê duyệt của tôi
    public function myTasks()
    {
        $pendingSteps = ApprovalStep::where('status', 'pending')
            ->where('approver_id', auth()->id())
            ->with(['request.workflow'])
            ->orderBy('assigned_at', 'asc')
            ->paginate(10);

        return view('approval.my-tasks', compact('pendingSteps'));
    }

    // Xử lý phê duyệt một bước
    public function processStep(ApprovalStep $step, Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'comments' => 'nullable|string',
            'rejection_reason' => 'required_if:action,reject|string|min:1',
        ]);

        try {
            if ($validated['action'] === 'approve') {
                $step->approve(auth()->id(), $validated['comments'] ?? null);
                $message = 'Bước đã được phê duyệt thành công!';
            } else {
                $rejectionReason = $validated['rejection_reason'] ?? '';
                $step->reject(auth()->id(), $rejectionReason, $validated['comments'] ?? null);
                $message = 'Bước đã bị từ chối!';
            }

            return redirect()->route('approval.show', $step->request)
                ->with('success', $message);

        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // API: Lấy schools theo university
    public function getSchoolsByUniversity(University $university)
    {
        if (!$university) {
            return response()->json(['error' => 'University not found'], 404);
        }
        
        $schools = $university->schools()->active()->get(['id', 'name', 'code']);
        return response()->json($schools);
    }

    // API: Lấy departments theo school
    public function getDepartmentsBySchool(School $school)
    {
        if (!$school) {
            return response()->json(['error' => 'School not found'], 404);
        }
        
        $departments = $school->departments()->active()->get(['id', 'name', 'code']);
        return response()->json($departments);
    }

    // Form tạo Trường mới (theo quy trình CREATE_SCHOOL)
    public function createSchool()
    {
        $workflow = ApprovalWorkflow::where('code', 'CREATE_SCHOOL')->first();
        if (!$workflow) {
            return redirect()->route('approval.index')
                ->with('error', 'Không tìm thấy quy trình tạo Trường!');
        }

        $universities = University::active()->get();
        return view('approval.create-school', compact('workflow', 'universities'));
    }

    // Form tạo Khoa mới (theo quy trình CREATE_DEPARTMENT)
    public function createDepartment()
    {
        $workflow = ApprovalWorkflow::where('code', 'CREATE_DEPARTMENT')->first();
        if (!$workflow) {
            return redirect()->route('approval.index')
                ->with('error', 'Không tìm thấy quy trình tạo Khoa!');
        }

        $universities = University::with('schools')->active()->get();
        return view('approval.create-department', compact('workflow', 'universities'));
    }

    // Lưu yêu cầu tạo Trường
    public function storeSchool(Request $request)
    {
        $validated = $request->validate([
            'university_id' => 'required|exists:universities,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'full_name' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'dean_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'established_date' => 'nullable|date',
            'requester_name' => 'required|string|max:255',
            'requester_email' => 'required|email',
            'requester_department' => 'nullable|string|max:255',
        ]);

        $workflow = ApprovalWorkflow::where('code', 'CREATE_SCHOOL')->firstOrFail();

        DB::beginTransaction();
        try {
            $entityData = [
                'university_id' => $validated['university_id'],
                'name' => $validated['name'],
                'code' => $validated['code'],
                'full_name' => $validated['full_name'],
                'description' => $validated['description'],
                'dean_name' => $validated['dean_name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'address' => $validated['address'],
                'established_date' => $validated['established_date'],
                'is_active' => false, // Sẽ được kích hoạt sau khi phê duyệt
            ];

            $approvalRequest = $workflow->createRequest([
                'title' => 'Tạo Trường mới: ' . $validated['name'],
                'description' => 'Yêu cầu tạo Trường mới với mã: ' . $validated['code'],
                'entity_type' => $workflow->entity_type,
                'entity_data' => $entityData,
                'requester_id' => 1,
                'requester_name' => $validated['requester_name'],
                'requester_email' => $validated['requester_email'],
                'requester_department' => $validated['requester_department'],
                'status' => 'draft',
            ]);

            DB::commit();

            return redirect()->route('approval.show', $approvalRequest)
                ->with('success', 'Yêu cầu tạo Trường đã được tạo thành công!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // Lưu yêu cầu tạo Khoa
    public function storeDepartment(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'full_name' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'head_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'office_location' => 'nullable|string|max:255',
            'established_date' => 'nullable|date',
            'student_count' => 'nullable|integer|min:0',
            'staff_count' => 'nullable|integer|min:0',
            'requester_name' => 'required|string|max:255',
            'requester_email' => 'required|email',
            'requester_department' => 'nullable|string|max:255',
        ]);

        $workflow = ApprovalWorkflow::where('code', 'CREATE_DEPARTMENT')->firstOrFail();

        DB::beginTransaction();
        try {
            $entityData = [
                'school_id' => $validated['school_id'],
                'name' => $validated['name'],
                'code' => $validated['code'],
                'full_name' => $validated['full_name'],
                'description' => $validated['description'],
                'head_name' => $validated['head_name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'office_location' => $validated['office_location'],
                'established_date' => $validated['established_date'],
                'student_count' => $validated['student_count'] ?? 0,
                'staff_count' => $validated['staff_count'] ?? 0,
                'is_active' => false, // Sẽ được kích hoạt sau khi phê duyệt
            ];

            $approvalRequest = $workflow->createRequest([
                'title' => 'Tạo Khoa mới: ' . $validated['name'],
                'description' => 'Yêu cầu tạo Khoa mới với mã: ' . $validated['code'],
                'entity_type' => $workflow->entity_type,
                'entity_data' => $entityData,
                'requester_id' => 1,
                'requester_name' => $validated['requester_name'],
                'requester_email' => $validated['requester_email'],
                'requester_department' => $validated['requester_department'],
                'status' => 'draft',
            ]);

            DB::commit();

            return redirect()->route('approval.show', $approvalRequest)
                ->with('success', 'Yêu cầu tạo Khoa đã được tạo thành công!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
