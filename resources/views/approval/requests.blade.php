@extends('layouts.app')

@section('title', 'Danh sách yêu cầu phê duyệt')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Danh sách yêu cầu phê duyệt</h1>
                <p class="text-muted">Quản lý tất cả các yêu cầu trong hệ thống</p>
            </div>
            <div>
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-plus me-2"></i>Tạo yêu cầu mới
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('approval.create-school') }}">
                            <i class="fas fa-graduation-cap me-2"></i>Tạo Trường mới
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('approval.create-department') }}">
                            <i class="fas fa-users me-2"></i>Tạo Khoa mới
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('approval.requests') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Tất cả trạng thái</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Nháp</option>
                        <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Đã gửi</option>
                        <option value="in_review" {{ request('status') === 'in_review' ? 'selected' : '' }}>Đang xem xét</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Đã phê duyệt</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Bị từ chối</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="workflow_id" class="form-label">Quy trình</label>
                    <select class="form-select" id="workflow_id" name="workflow_id">
                        <option value="">Tất cả quy trình</option>
                        @foreach($workflows as $workflow)
                            <option value="{{ $workflow->id }}" {{ request('workflow_id') == $workflow->id ? 'selected' : '' }}>
                                {{ $workflow->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="search" class="form-label">Tìm kiếm</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Mã yêu cầu, tiêu đề, người yêu cầu...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-search me-2"></i>Lọc
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Requests Table -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>
                Danh sách yêu cầu ({{ $requests->total() }})
            </h5>
            @if(request()->hasAny(['status', 'workflow_id', 'search']))
                <a href="{{ route('approval.requests') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-times me-1"></i>Xóa bộ lọc
                </a>
            @endif
        </div>
    </div>
    <div class="card-body p-0">
        @if($requests->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Mã yêu cầu</th>
                            <th>Tiêu đề</th>
                            <th>Quy trình</th>
                            <th>Người yêu cầu</th>
                            <th>Trạng thái</th>
                            <th>Tiến độ</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $request)
                        <tr>
                            <td>
                                <code>{{ $request->request_code }}</code>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $request->title }}</strong>
                                    @if($request->description)
                                        <br>
                                        <small class="text-muted">{{ Str::limit($request->description, 60) }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $request->workflow->name }}
                                </span>
                                <br>
                                <small class="text-muted">{{ $request->entity_type }}</small>
                            </td>
                            <td>
                                <div>
                                    {{ $request->requester_name }}
                                    <br>
                                    <small class="text-muted">{{ $request->requester_email }}</small>
                                    @if($request->requester_department)
                                        <br>
                                        <small class="text-muted">{{ $request->requester_department }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
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
                            </td>
                            <td>
                                @php
                                    $progress = $request->getProgressPercentage();
                                @endphp
                                <div class="progress mb-1" style="height: 6px;">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%"></div>
                                </div>
                                <small class="text-muted">{{ $progress }}%</small>
                                @if($request->current_step > 0)
                                    <br>
                                    <small class="text-muted">Bước {{ $request->current_step }}/{{ $request->steps->count() }}</small>
                                @endif
                            </td>
                            <td>
                                <div>
                                    {{ $request->created_at->format('d/m/Y') }}
                                    <br>
                                    <small class="text-muted">{{ $request->created_at->format('H:i') }}</small>
                                    <br>
                                    <small class="text-muted">{{ $request->created_at->diffForHumans() }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group-vertical" role="group">
                                    <a href="{{ route('approval.show', $request) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>Xem
                                    </a>
                                    @if($request->status === 'draft')
                                        <form action="{{ route('approval.submit', $request) }}" method="POST" class="mt-1">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success btn-sm w-100" 
                                                    onclick="return confirm('Bạn có chắc chắn muốn gửi yêu cầu này để phê duyệt?')">
                                                <i class="fas fa-paper-plane me-1"></i>Gửi
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($requests->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">
                                Hiển thị {{ $requests->firstItem() }} đến {{ $requests->lastItem() }} 
                                trong tổng số {{ $requests->total() }} yêu cầu
                            </small>
                        </div>
                        <div>
                            {{ $requests->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Không tìm thấy yêu cầu nào</h5>
                <p class="text-muted">
                    @if(request()->hasAny(['status', 'workflow_id', 'search']))
                        Thử thay đổi bộ lọc hoặc tạo yêu cầu mới.
                    @else
                        Chưa có yêu cầu nào trong hệ thống. Hãy tạo yêu cầu đầu tiên!
                    @endif
                </p>
                <div class="mt-3">
                    <a href="{{ route('approval.create-school') }}" class="btn btn-primary me-2">
                        <i class="fas fa-graduation-cap me-2"></i>Tạo Trường mới
                    </a>
                    <a href="{{ route('approval.create-department') }}" class="btn btn-success">
                        <i class="fas fa-users me-2"></i>Tạo Khoa mới
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when filters change
    const statusSelect = document.getElementById('status');
    const workflowSelect = document.getElementById('workflow_id');
    
    [statusSelect, workflowSelect].forEach(select => {
        select.addEventListener('change', function() {
            // Auto-submit form after a short delay
            setTimeout(() => {
                this.form.submit();
            }, 100);
        });
    });
    
    // Search with Enter key
    const searchInput = document.getElementById('search');
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            this.form.submit();
        }
    });
    
    // Add tooltips to progress bars
    const progressBars = document.querySelectorAll('.progress');
    progressBars.forEach(bar => {
        const percentage = bar.querySelector('.progress-bar').style.width;
        bar.setAttribute('title', `Tiến độ: ${percentage}`);
        new bootstrap.Tooltip(bar);
    });
    
    // Highlight current row on hover
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'rgba(102, 126, 234, 0.1)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
    
    // Auto-refresh page every 60 seconds for pending requests
    @if(request('status') === 'submitted' || request('status') === 'in_review' || !request('status'))
    setInterval(function() {
        // Only refresh if user is still on the page
        if (!document.hidden) {
            location.reload();
        }
    }, 60000);
    @endif
});
</script>
@endpush

