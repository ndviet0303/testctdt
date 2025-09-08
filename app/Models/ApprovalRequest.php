<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class ApprovalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'workflow_id',
        'request_code',
        'title',
        'description',
        'requester_id',
        'requester_name',
        'requester_email',
        'requester_department',
        'entity_type',
        'entity_id',
        'entity_data',
        'original_data',
        'status',
        'current_step',
        'rejection_reason',
        'approval_history',
        'submitted_at',
        'approved_at',
        'rejected_at',
        'completed_at',
        'metadata',
    ];

    protected $casts = [
        'entity_data' => 'array',
        'original_data' => 'array',
        'approval_history' => 'array',
        'metadata' => 'array',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function workflow(): BelongsTo
    {
        return $this->belongsTo(ApprovalWorkflow::class, 'workflow_id');
    }

    public function steps(): HasMany
    {
        return $this->hasMany(ApprovalStep::class, 'request_id')->orderBy('step_order');
    }

    public function currentStepRecord()
    {
        return $this->steps()->where('step_order', $this->current_step)->first();
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['submitted', 'in_review']);
    }

    public function scopeByRequester($query, $requesterId)
    {
        return $query->where('requester_id', $requesterId);
    }

    public function scopeByEntityType($query, $entityType)
    {
        return $query->where('entity_type', $entityType);
    }

    // Business Logic Methods
    public function submit()
    {
        if ($this->status !== 'draft') {
            throw new \Exception('Chỉ có thể submit yêu cầu ở trạng thái draft');
        }

        $this->update([
            'status' => 'submitted',
            'submitted_at' => now(),
            'current_step' => 1
        ]);

        // Assign first step
        $firstStep = $this->steps()->where('step_order', 1)->first();
        if ($firstStep) {
            $firstStep->update([
                'status' => 'pending',
                'assigned_at' => now()
            ]);
        }

        $this->addToHistory('submitted', 'Yêu cầu được gửi');
        
        return $this;
    }

    public function approve($approverId, $comments = null)
    {
        $currentStep = $this->currentStepRecord();
        
        if (!$currentStep || $currentStep->status !== 'pending') {
            throw new \Exception('Không thể phê duyệt ở bước hiện tại');
        }

        // Approve current step
        $currentStep->update([
            'status' => 'approved',
            'approver_id' => $approverId,
            'comments' => $comments,
            'completed_at' => now(),
            'processing_time' => now()->diffInMinutes($currentStep->assigned_at)
        ]);

        $this->addToHistory('step_approved', "Bước {$currentStep->step_name} được phê duyệt", [
            'step_order' => $currentStep->step_order,
            'approver_id' => $approverId,
            'comments' => $comments
        ]);

        // Check if this is the last step
        $nextStep = $this->steps()->where('step_order', $this->current_step + 1)->first();
        
        if ($nextStep) {
            // Move to next step
            $this->update([
                'current_step' => $nextStep->step_order,
                'status' => 'in_review'
            ]);

            $nextStep->update([
                'status' => 'pending',
                'assigned_at' => now()
            ]);
        } else {
            // All steps completed
            $this->update([
                'status' => 'approved',
                'approved_at' => now()
            ]);

            $this->addToHistory('approved', 'Yêu cầu được phê duyệt hoàn toàn');
            
            // Execute the approved action
            $this->executeApprovedAction();
        }

        return $this;
    }

    public function reject($approverId, $reason, $comments = null)
    {
        $currentStep = $this->currentStepRecord();
        
        if (!$currentStep || $currentStep->status !== 'pending') {
            throw new \Exception('Không thể từ chối ở bước hiện tại');
        }

        // Reject current step
        $currentStep->update([
            'status' => 'rejected',
            'approver_id' => $approverId,
            'rejection_reason' => $reason,
            'comments' => $comments,
            'completed_at' => now(),
            'processing_time' => now()->diffInMinutes($currentStep->assigned_at)
        ]);

        // Reject entire request
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'rejected_at' => now()
        ]);

        $this->addToHistory('rejected', "Yêu cầu bị từ chối tại bước {$currentStep->step_name}", [
            'step_order' => $currentStep->step_order,
            'approver_id' => $approverId,
            'reason' => $reason,
            'comments' => $comments
        ]);

        return $this;
    }

    private function executeApprovedAction()
    {
        try {
            switch ($this->entity_type) {
                case 'school':
                    $this->executeSchoolAction();
                    break;
                case 'department':
                    $this->executeDepartmentAction();
                    break;
                case 'university':
                    $this->executeUniversityAction();
                    break;
            }

            $this->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);

            $this->addToHistory('completed', 'Hành động được thực hiện thành công');

        } catch (\Exception $e) {
            $this->addToHistory('execution_failed', 'Lỗi khi thực hiện hành động: ' . $e->getMessage());
            throw $e;
        }
    }

    private function executeSchoolAction()
    {
        $workflow = $this->workflow;
        
        switch ($workflow->action_type) {
            case 'create':
                School::create($this->entity_data);
                break;
            case 'update':
                School::find($this->entity_id)->update($this->entity_data);
                break;
            case 'activate':
                School::find($this->entity_id)->update(['is_active' => true]);
                break;
            case 'deactivate':
                School::find($this->entity_id)->update(['is_active' => false]);
                break;
        }
    }

    private function executeDepartmentAction()
    {
        $workflow = $this->workflow;
        
        switch ($workflow->action_type) {
            case 'create':
                Department::create($this->entity_data);
                break;
            case 'update':
                Department::find($this->entity_id)->update($this->entity_data);
                break;
            case 'activate':
                Department::find($this->entity_id)->update(['is_active' => true]);
                break;
            case 'deactivate':
                Department::find($this->entity_id)->update(['is_active' => false]);
                break;
        }
    }

    private function executeUniversityAction()
    {
        $workflow = $this->workflow;
        
        switch ($workflow->action_type) {
            case 'update':
                University::find($this->entity_id)->update($this->entity_data);
                break;
        }
    }

    private function addToHistory($action, $message, $data = [])
    {
        $history = $this->approval_history ?? [];
        
        $history[] = [
            'action' => $action,
            'message' => $message,
            'data' => $data,
            'timestamp' => now()->toISOString(),
            'user_id' => auth()->id() ?? null,
        ];

        $this->update(['approval_history' => $history]);
    }

    // Helper methods
    public function getProgressPercentage()
    {
        $totalSteps = $this->steps()->count();
        $completedSteps = $this->steps()->whereIn('status', ['approved', 'skipped'])->count();
        
        return $totalSteps > 0 ? round(($completedSteps / $totalSteps) * 100) : 0;
    }

    public function getProcessingTime()
    {
        if ($this->submitted_at && $this->completed_at) {
            return $this->submitted_at->diffForHumans($this->completed_at, true);
        }
        
        if ($this->submitted_at) {
            return $this->submitted_at->diffForHumans(now(), true) . ' (đang xử lý)';
        }
        
        return null;
    }

    public function canBeApproved()
    {
        return in_array($this->status, ['submitted', 'in_review']);
    }

    public function canBeRejected()
    {
        return in_array($this->status, ['submitted', 'in_review']);
    }
}
