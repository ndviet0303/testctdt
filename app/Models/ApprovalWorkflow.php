<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApprovalWorkflow extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'entity_type',
        'action_type',
        'workflow_steps',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'workflow_steps' => 'array',
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    // Relationships
    public function approvalRequests(): HasMany
    {
        return $this->hasMany(ApprovalRequest::class, 'workflow_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForEntity($query, $entityType, $actionType = null)
    {
        $query->where('entity_type', $entityType);
        
        if ($actionType) {
            $query->where('action_type', $actionType);
        }
        
        return $query;
    }

    // Business Logic Methods
    public function getStepsCount()
    {
        return count($this->workflow_steps ?? []);
    }

    public function getStepByOrder($order)
    {
        $steps = $this->workflow_steps ?? [];
        return collect($steps)->firstWhere('order', $order);
    }

    public function createRequest($requestData)
    {
        $request = new ApprovalRequest();
        $request->workflow_id = $this->id;
        $request->request_code = $this->generateRequestCode();
        $request->fill($requestData);
        $request->save();

        // Tạo các bước phê duyệt
        $this->createApprovalSteps($request);

        return $request;
    }

    private function generateRequestCode()
    {
        $prefix = strtoupper($this->code);
        $date = date('Ymd');
        $sequence = ApprovalRequest::where('workflow_id', $this->id)
            ->whereDate('created_at', today())
            ->count() + 1;
        
        return sprintf('%s-%s-%04d', $prefix, $date, $sequence);
    }

    private function createApprovalSteps(ApprovalRequest $request)
    {
        $steps = $this->workflow_steps ?? [];
        
        foreach ($steps as $stepConfig) {
            ApprovalStep::create([
                'request_id' => $request->id,
                'step_order' => $stepConfig['order'],
                'step_name' => $stepConfig['name'],
                'step_description' => $stepConfig['description'] ?? null,
                'approver_role' => $stepConfig['approver_role'] ?? null,
                'is_required' => $stepConfig['is_required'] ?? true,
                'can_delegate' => $stepConfig['can_delegate'] ?? false,
                'timeout_hours' => $stepConfig['timeout_hours'] ?? null,
                'escalation_rules' => $stepConfig['escalation_rules'] ?? null,
            ]);
        }
    }

    // Static methods for common workflows
    public static function getSchoolCreationWorkflow()
    {
        return self::active()->forEntity('school', 'create')->first();
    }

    public static function getSchoolActivationWorkflow()
    {
        return self::active()->forEntity('school', 'activate')->first();
    }

    public static function getDepartmentCreationWorkflow()
    {
        return self::active()->forEntity('department', 'create')->first();
    }
}
