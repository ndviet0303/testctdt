@extends('layouts.app')

@section('title', 'Chi tiết yêu cầu: ' . $request->request_code)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Chi tiết yêu cầu</h1>
                <div class="d-flex align-items-center mt-2">
                    <code class="me-3">{{ $request->request_code }}</code>
                    @if($request->status === 'draft')
                        <span class="badge bg-secondary">Nháp</span>
                    @elseif($request->status === 'submitted')
                        <span class="badge bg-warning">Đã gửi</span>
                    @elseif($request->status === 'in_review')
                        <span class="badge bg-info">Đang xem xét</span>
                    @elseif($request->status === 'approved')
                        <span class="badge bg-success">Đã phê duyệt</span>
                    @elseif($request->status === 'rejected')
                        <span class="badge bg-danger">Bị từ chối</span>
                    @elseif($request->status === 'completed')
                        <span class="badge bg-primary">Hoàn thành</span>
                    @endif
                </div>
            </div>
            <div>
                <a href="{{ route('approval.requests') }}" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                </a>
                @if($request->status === 'draft')
                    <form action="{{ route('approval.submit', $request) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success" onclick="return confirm('Bạn có chắc chắn muốn gửi yêu cầu này để phê duyệt?')">
                            <i class="fas fa-paper-plane me-2"></i>Gửi phê duyệt
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Request Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Thông tin yêu cầu
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Tiêu đề:</strong>
                        <p>{{ $request->title }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Quy trình:</strong>
                        <p>
                            <span class="badge bg-info">{{ $request->workflow->name }}</span>
                        </p>
                    </div>
                </div>

                @if($request->description)
                <div class="mb-3">
                    <strong>Mô tả:</strong>
                    <p>{{ $request->description }}</p>
                </div>
                @endif

                <div class="row">
                    <div class="col-md-4">
                        <strong>Người yêu cầu:</strong>
                        <p>{{ $request->requester_name }}</p>
                    </div>
                    <div class="col-md-4">
                        <strong>Email:</strong>
                        <p>{{ $request->requester_email }}</p>
                    </div>
                    <div class="col-md-4">
                        <strong>Bộ phận:</strong>
                        <p>{{ $request->requester_department ?? 'Không có' }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <strong>Ngày tạo:</strong>
                        <p>{{ $request->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @if($request->submitted_at)
                    <div class="col-md-4">
                        <strong>Ngày gửi:</strong>
                        <p>{{ $request->submitted_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                    @if($request->completed_at)
                    <div class="col-md-4">
                        <strong>Ngày hoàn thành:</strong>
                        <p>{{ $request->completed_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                </div>

                <!-- Progress -->
                @php
                    $progress = $request->getProgressPercentage();
                @endphp
                <div class="mb-3">
                    <strong>Tiến độ:</strong>
                    <div class="progress mt-2" style="height: 10px;">
                        <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%" 
                             aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                            {{ $progress }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Entity Data -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-database me-2"></i>
                    Dữ liệu đối tượng
                </h5>
            </div>
            <div class="card-body">
                @if($request->entity_type === 'school')
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Tên Trường:</strong>
                            <p>{{ $request->entity_data['name'] ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Mã Trường:</strong>
                            <p>{{ $request->entity_data['code'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Tên đầy đủ:</strong>
                            <p>{{ $request->entity_data['full_name'] ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Trưởng Trường:</strong>
                            <p>{{ $request->entity_data['dean_name'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                    @if(isset($request->entity_data['description']))
                    <div class="mb-3">
                        <strong>Mô tả:</strong>
                        <p>{{ $request->entity_data['description'] }}</p>
                    </div>
                    @endif
                @elseif($request->entity_type === 'department')
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Tên Khoa:</strong>
                            <p>{{ $request->entity_data['name'] ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Mã Khoa:</strong>
                            <p>{{ $request->entity_data['code'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Trưởng Khoa:</strong>
                            <p>{{ $request->entity_data['head_name'] ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Số sinh viên:</strong>
                            <p>{{ $request->entity_data['student_count'] ?? 0 }}</p>
                        </div>
                    </div>
                @endif

                <!-- Raw data (collapsible) -->
                <div class="mt-3">
                    <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#rawData">
                        <i class="fas fa-code me-2"></i>Xem dữ liệu thô
                    </button>
                    <div class="collapse mt-2" id="rawData">
                        <pre class="bg-light p-3 rounded"><code>{{ json_encode($request->entity_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approval History -->
        @if($request->approval_history)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>
                    Lịch sử phê duyệt
                </h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($request->approval_history as $history)
                    <div class="timeline-item @if($history['action'] === 'approved') completed @elseif($history['action'] === 'rejected') rejected @endif">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $history['message'] }}</h6>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ \Carbon\Carbon::parse($history['timestamp'])->format('d/m/Y H:i:s') }}
                                </small>
                                @if(isset($history['data']) && !empty($history['data']))
                                <div class="mt-2">
                                    <small class="text-muted">
                                        @if(isset($history['data']['comments']))
                                            <strong>Nhận xét:</strong> {{ $history['data']['comments'] }}<br>
                                        @endif
                                        @if(isset($history['data']['reason']))
                                            <strong>Lý do:</strong> {{ $history['data']['reason'] }}
                                        @endif
                                    </small>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <!-- Approval Steps -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-tasks me-2"></i>
                    Các bước phê duyệt
                </h5>
            </div>
            <div class="card-body">
                @if($request->steps->count() > 0)
                    <div class="timeline">
                        @foreach($request->steps as $step)
                        <div class="timeline-item 
                            @if($step->status === 'approved') completed 
                            @elseif($step->status === 'rejected') rejected 
                            @endif">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <span class="badge 
                                        @if($step->status === 'pending') bg-warning
                                        @elseif($step->status === 'in_progress') bg-info
                                        @elseif($step->status === 'approved') bg-success
                                        @elseif($step->status === 'rejected') bg-danger
                                        @else bg-secondary
                                        @endif rounded-pill">
                                        {{ $step->step_order }}
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">{{ $step->step_name }}</h6>
                                    <p class="text-muted small mb-2">{{ $step->step_description }}</p>
                                    
                                    <div class="d-flex align-items-center mb-2">
                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i>{{ $step->approver_role }}
                                        </small>
                                        @if($step->timeout_hours)
                                        <small class="text-warning ms-2">
                                            <i class="fas fa-clock me-1"></i>{{ $step->timeout_hours }}h
                                        </small>
                                        @endif
                                    </div>

                                    <div class="mb-2">
                                        @if($step->status === 'pending')
                                            <span class="badge bg-warning">Chờ xử lý</span>
                                        @elseif($step->status === 'in_progress')
                                            <span class="badge bg-info">Đang xử lý</span>
                                        @elseif($step->status === 'approved')
                                            <span class="badge bg-success">Đã phê duyệt</span>
                                        @elseif($step->status === 'rejected')
                                            <span class="badge bg-danger">Đã từ chối</span>
                                        @elseif($step->status === 'skipped')
                                            <span class="badge bg-secondary">Đã bỏ qua</span>
                                        @endif
                                    </div>

                                    @if($step->assigned_at)
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        Giao: {{ $step->assigned_at->format('d/m/Y H:i') }}
                                    </small>
                                    @endif

                                    @if($step->completed_at)
                                    <small class="text-muted d-block">
                                        <i class="fas fa-check me-1"></i>
                                        Hoàn thành: {{ $step->completed_at->format('d/m/Y H:i') }}
                                    </small>
                                    @endif

                                    @if($step->comments)
                                    <div class="mt-2 p-2 bg-light rounded">
                                        <small><strong>Nhận xét:</strong> {{ $step->comments }}</small>
                                    </div>
                                    @endif

                                    @if($step->rejection_reason)
                                    <div class="mt-2 p-2 bg-danger-subtle rounded">
                                        <small><strong>Lý do từ chối:</strong> {{ $step->rejection_reason }}</small>
                                    </div>
                                    @endif

                                    <!-- Action buttons for pending steps -->
                                    @if($step->status === 'pending' && $step->approver_id == auth()->id())
                                    <div class="mt-3">
                                        <button class="btn btn-success btn-sm me-2" onclick="openApprovalModal({{ $step->id }}, 'approve', '{{ $step->step_name }}')">
                                            <i class="fas fa-check me-1"></i>Phê duyệt
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="openApprovalModal({{ $step->id }}, 'reject', '{{ $step->step_name }}')">
                                            <i class="fas fa-times me-1"></i>Từ chối
                                        </button>
                                    </div>
                                    @elseif($step->status === 'pending' && !$step->approver_id)
                                    <div class="mt-3">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Chưa có người phê duyệt được giao cho bước này
                                        </small>
                                    </div>
                                    @elseif($step->status === 'pending')
                                    <div class="mt-3">
                                        <small class="text-muted">
                                            <i class="fas fa-user-clock me-1"></i>
                                            Đang chờ {{ $step->approver_name ?? $step->approver_role }} xử lý
                                        </small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-info-circle fa-2x text-muted mb-2"></i>
                        <p class="text-muted">Chưa có bước phê duyệt nào được tạo.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Approval Modal -->
<div class="modal fade" id="approvalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle me-2"></i>
                    <span id="modalTitle">Phê duyệt bước</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="approvalForm" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="approvalAction" name="action" value="">
                    
                    <div class="mb-3">
                        <label for="comments" class="form-label">Nhận xét</label>
                        <textarea class="form-control" id="comments" name="comments" rows="3" 
                                  placeholder="Nhập nhận xét của bạn..."></textarea>
                    </div>

                    <div class="mb-3" id="rejectionReasonGroup" style="display: none;">
                        <label for="rejection_reason" class="form-label">Lý do từ chối <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" 
                                  placeholder="Nhập lý do từ chối..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn" id="submitBtn">
                        <i class="fas fa-check me-2"></i>
                        <span id="submitText">Phê duyệt</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentStepId = null;
let currentAction = null;

function openApprovalModal(stepId, action, stepName) {
    currentStepId = stepId;
    currentAction = action;
    
    const modal = document.getElementById('approvalModal');
    const modalTitle = document.getElementById('modalTitle');
    const approvalAction = document.getElementById('approvalAction');
    const rejectionReasonGroup = document.getElementById('rejectionReasonGroup');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const form = document.getElementById('approvalForm');
    
    // Reset form
    form.reset();
    
    // Set action
    approvalAction.value = action;
    
    // Update form action
    form.action = `/approval/steps/${stepId}/process`;
    
    if (action === 'approve') {
        modalTitle.innerHTML = '<i class="fas fa-check-circle me-2"></i>Phê duyệt bước: ' + stepName;
        rejectionReasonGroup.style.display = 'none';
        submitBtn.className = 'btn btn-success';
        submitText.textContent = 'Phê duyệt';
        document.getElementById('rejection_reason').removeAttribute('required');
    } else {
        modalTitle.innerHTML = '<i class="fas fa-times-circle me-2"></i>Từ chối bước: ' + stepName;
        rejectionReasonGroup.style.display = 'block';
        submitBtn.className = 'btn btn-danger';
        submitText.textContent = 'Từ chối';
        document.getElementById('rejection_reason').setAttribute('required', 'required');
    }
    
    new bootstrap.Modal(modal).show();
}

document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.getElementById('approvalForm');
    form.addEventListener('submit', function(e) {
        const action = document.getElementById('approvalAction').value;
        const rejectionReasonField = document.getElementById('rejection_reason');
        const rejectionReason = rejectionReasonField.value;
        
        // Clear rejection_reason if action is approve
        if (action === 'approve') {
            rejectionReasonField.value = '';
            rejectionReasonField.removeAttribute('name');
        } else {
            rejectionReasonField.setAttribute('name', 'rejection_reason');
            if (!rejectionReason || !rejectionReason.trim()) {
                e.preventDefault();
                alert('Vui lòng nhập lý do từ chối!');
                return;
            }
        }
        
        const confirmMessage = action === 'approve' 
            ? 'Bạn có chắc chắn muốn phê duyệt bước này?' 
            : 'Bạn có chắc chắn muốn từ chối bước này?';
            
        if (!confirm(confirmMessage)) {
            e.preventDefault();
        }
    });
    
    // Auto-refresh page every 30 seconds if request is in progress
    @if(in_array($request->status, ['submitted', 'in_review']))
    setInterval(function() {
        location.reload();
    }, 30000);
    @endif
});
</script>
@endpush
