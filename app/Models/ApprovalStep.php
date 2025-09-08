<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'step_order',
        'step_name',
        'step_description',
        'approver_id',
        'approver_name',
        'approver_role',
        'approver_department',
        'status',
        'comments',
        'rejection_reason',
        'step_data',
        'assigned_at',
        'started_at',
        'completed_at',
        'processing_time',
        'is_required',
        'can_delegate',
        'timeout_hours',
        'escalation_rules',
        'metadata',
    ];

    protected $casts = [
        'step_data' => 'array',
        'escalation_rules' => 'array',
        'metadata' => 'array',
        'assigned_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_required' => 'boolean',
        'can_delegate' => 'boolean',
    ];

    // Relationships
    public function request(): BelongsTo
    {
        return $this->belongsTo(ApprovalRequest::class, 'request_id');
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByApprover($query, $approverId)
    {
        return $query->where('approver_id', $approverId);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'pending')
            ->whereNotNull('timeout_hours')
            ->whereRaw('assigned_at + INTERVAL timeout_hours HOUR < NOW()');
    }

    // Business Logic Methods
    public function start($approverId)
    {
        if ($this->status !== 'pending') {
            throw new \Exception('Chỉ có thể bắt đầu bước ở trạng thái pending');
        }

        $this->update([
            'status' => 'in_progress',
            'approver_id' => $approverId,
            'started_at' => now()
        ]);

        return $this;
    }

    public function approve($approverId, $comments = null)
    {
        if (!in_array($this->status, ['pending', 'in_progress'])) {
            throw new \Exception('Không thể phê duyệt bước này');
        }

        $this->update([
            'status' => 'approved',
            'approver_id' => $approverId,
            'comments' => $comments,
            'completed_at' => now(),
            'processing_time' => $this->assigned_at ? now()->diffInMinutes($this->assigned_at) : null
        ]);

        // Continue with request approval
        $this->request->approve($approverId, $comments);

        return $this;
    }

    public function reject($approverId, $reason, $comments = null)
    {
        if (!in_array($this->status, ['pending', 'in_progress'])) {
            throw new \Exception('Không thể từ chối bước này');
        }

        $this->update([
            'status' => 'rejected',
            'approver_id' => $approverId,
            'rejection_reason' => $reason,
            'comments' => $comments,
            'completed_at' => now(),
            'processing_time' => $this->assigned_at ? now()->diffInMinutes($this->assigned_at) : null
        ]);

        // Reject entire request
        $this->request->reject($approverId, $reason, $comments);

        return $this;
    }

    public function delegate($fromApproverId, $toApproverId, $reason = null)
    {
        if (!$this->can_delegate) {
            throw new \Exception('Bước này không thể ủy quyền');
        }

        if ($this->status !== 'pending') {
            throw new \Exception('Chỉ có thể ủy quyền bước ở trạng thái pending');
        }

        $originalApprover = $this->approver_id;
        
        $this->update([
            'approver_id' => $toApproverId,
            'metadata' => array_merge($this->metadata ?? [], [
                'delegated_from' => $originalApprover,
                'delegation_reason' => $reason,
                'delegated_at' => now()->toISOString()
            ])
        ]);

        return $this;
    }

    public function skip($reason = null)
    {
        if ($this->is_required) {
            throw new \Exception('Không thể bỏ qua bước bắt buộc');
        }

        $this->update([
            'status' => 'skipped',
            'comments' => $reason,
            'completed_at' => now(),
            'processing_time' => $this->assigned_at ? now()->diffInMinutes($this->assigned_at) : null
        ]);

        return $this;
    }

    // Helper methods
    public function isOverdue()
    {
        if (!$this->timeout_hours || $this->status !== 'pending') {
            return false;
        }

        return $this->assigned_at && 
               $this->assigned_at->addHours($this->timeout_hours)->isPast();
    }

    public function getTimeRemaining()
    {
        if (!$this->timeout_hours || $this->status !== 'pending') {
            return null;
        }

        if (!$this->assigned_at) {
            return null;
        }

        $deadline = $this->assigned_at->addHours($this->timeout_hours);
        
        if ($deadline->isPast()) {
            return 'Quá hạn ' . $deadline->diffForHumans();
        }

        return 'Còn ' . now()->diffForHumans($deadline, true);
    }

    public function canBeProcessed()
    {
        return in_array($this->status, ['pending', 'in_progress']);
    }

    public function getStatusLabel()
    {
        return match($this->status) {
            'pending' => 'Chờ xử lý',
            'in_progress' => 'Đang xử lý',
            'approved' => 'Đã phê duyệt',
            'rejected' => 'Đã từ chối',
            'skipped' => 'Đã bỏ qua',
            'cancelled' => 'Đã hủy',
            default => ucfirst($this->status)
        };
    }

    public function getStatusColor()
    {
        return match($this->status) {
            'pending' => 'warning',
            'in_progress' => 'info',
            'approved' => 'success',
            'rejected' => 'danger',
            'skipped' => 'secondary',
            'cancelled' => 'dark',
            default => 'primary'
        };
    }
}
