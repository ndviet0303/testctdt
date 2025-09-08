<?php

namespace App\Http\Controllers;

use App\Models\University;
use App\Models\School;
use App\Models\Department;
use App\Models\ApprovalWorkflow;
use App\Models\ApprovalRequest;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function dashboard()
    {
        $university = University::with(['schools.departments'])->first();
        
        // Nếu không có university nào, tạo data mặc định
        if (!$university) {
            $stats = [
                'total_schools' => 0,
                'total_departments' => 0,
                'total_students' => 0,
                'total_staff' => 0,
            ];
            $schools = collect([]);
            return view('dashboard', compact('university', 'stats', 'schools'));
        }
        
        $stats = [
            'total_schools' => $university->schools->count(),
            'total_departments' => $university->departments->count(),
            'total_students' => $university->departments->sum('student_count'),
            'total_staff' => $university->departments->sum('staff_count'),
        ];
        
        $schools = $university->schools;

        return view('dashboard', compact('university', 'stats', 'schools'));
    }

    public function index()
    {
        $universities = University::with(['schools.departments'])->active()->get();
        return view('organization.tree', compact('universities'));
    }

    public function tree()
    {
        $universities = University::with(['schools.departments'])
            ->active()
            ->get()
            ->map(function ($university) {
                return [
                    'id' => 'uni_' . $university->id,
                    'text' => $university->name . ' (' . $university->code . ')',
                    'type' => 'university',
                    'data' => $university,
                    'children' => $university->schools->map(function ($school) {
                        return [
                            'id' => 'school_' . $school->id,
                            'text' => $school->name . ' (' . $school->code . ')',
                            'type' => 'school',
                            'data' => $school,
                            'children' => $school->departments->map(function ($dept) {
                                return [
                                    'id' => 'dept_' . $dept->id,
                                    'text' => $dept->name . ' (' . $dept->code . ')',
                                    'type' => 'department',
                                    'data' => $dept,
                                ];
                            })->toArray()
                        ];
                    })->toArray()
                ];
            });

        return response()->json($universities);
    }

    public function getUniversityDetails($id)
    {
        $university = University::with(['schools.departments'])->findOrFail($id);
        
        $stats = [
            'schools_count' => $university->schools->count(),
            'departments_count' => $university->departments->count(),
            'total_students' => $university->departments->sum('student_count'),
            'total_staff' => $university->departments->sum('staff_count'),
        ];

        return response()->json([
            'university' => $university,
            'stats' => $stats
        ]);
    }

    public function getSchoolDetails($id)
    {
        $school = School::with(['university', 'departments'])->findOrFail($id);
        
        $stats = [
            'departments_count' => $school->departments->count(),
            'total_students' => $school->departments->sum('student_count'),
            'total_staff' => $school->departments->sum('staff_count'),
        ];

        return response()->json([
            'school' => $school,
            'stats' => $stats
        ]);
    }

    public function getDepartmentDetails($id)
    {
        $department = Department::with(['school.university'])->findOrFail($id);
        
        return response()->json([
            'department' => $department,
            'full_path' => $department->getFullPath()
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $results = [];

        if (strlen($query) >= 2) {
            // Search Universities
            $universities = University::where('name', 'like', "%{$query}%")
                ->orWhere('code', 'like', "%{$query}%")
                ->active()
                ->limit(5)
                ->get();

            foreach ($universities as $uni) {
                $results[] = [
                    'type' => 'university',
                    'id' => $uni->id,
                    'name' => $uni->name,
                    'code' => $uni->code,
                    'path' => $uni->name
                ];
            }

            // Search Schools
            $schools = School::with('university')
                ->where('name', 'like', "%{$query}%")
                ->orWhere('code', 'like', "%{$query}%")
                ->active()
                ->limit(5)
                ->get();

            foreach ($schools as $school) {
                $results[] = [
                    'type' => 'school',
                    'id' => $school->id,
                    'name' => $school->name,
                    'code' => $school->code,
                    'path' => $school->university->name . ' → ' . $school->name
                ];
            }

            // Search Departments
            $departments = Department::with('school.university')
                ->where('name', 'like', "%{$query}%")
                ->orWhere('code', 'like', "%{$query}%")
                ->active()
                ->limit(5)
                ->get();

            foreach ($departments as $dept) {
                $results[] = [
                    'type' => 'department',
                    'id' => $dept->id,
                    'name' => $dept->name,
                    'code' => $dept->code,
                    'path' => $dept->school->university->name . ' → ' . 
                             $dept->school->name . ' → ' . $dept->name
                ];
            }
        }

        return response()->json($results);
    }

    public function businessLogicDemo()
    {
        $workflows = ApprovalWorkflow::active()->get();
        $recentRequests = ApprovalRequest::with(['workflow', 'steps'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('business-logic.demo', compact('workflows', 'recentRequests'));
    }

    public function getWorkflows()
    {
        $workflows = ApprovalWorkflow::active()->get();
        return response()->json($workflows);
    }

    public function createRequest(ApprovalWorkflow $workflow, Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'entity_data' => 'required|array',
            'requester_name' => 'required|string|max:255',
            'requester_email' => 'required|email',
            'requester_department' => 'nullable|string|max:255',
        ]);

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

        return response()->json([
            'success' => true,
            'request' => $approvalRequest->load(['workflow', 'steps']),
            'message' => 'Yêu cầu đã được tạo thành công'
        ]);
    }
}
