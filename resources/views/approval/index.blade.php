@extends('layouts.app')

@section('title', 'Dashboard Quản lý Phê duyệt')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Dashboard Quản lý Phê duyệt</h1>
                <p class="text-muted">Tổng quan về các yêu cầu và quy trình phê duyệt</p>
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
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('approval.create') }}">
                            <i class="fas fa-cog me-2"></i>Yêu cầu tùy chỉnh
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-icon pending">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stats-number">{{ $stats['pending_requests'] }}</div>
            <div class="stats-label">Đang chờ xử lý</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-icon approved">
                <i class="fas fa-check"></i>
            </div>
            <div class="stats-number">{{ $stats['approved_today'] }}</div>
            <div class="stats-label">Được duyệt hôm nay</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-icon rejected">
                <i class="fas fa-times"></i>
            </div>
            <div class="stats-number">{{ $stats['rejected_today'] }}</div>
            <div class="stats-label">Bị từ chối hôm nay</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-icon workflows">
                <i class="fas fa-project-diagram"></i>
            </div>
            <div class="stats-number">{{ $stats['total_workflows'] }}</div>
            <div class="stats-label">Quy trình hoạt động</div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Pending Requests -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Yêu cầu đang chờ xử lý
                    </h5>
                    <a href="{{ route('approval.requests') }}" class="btn btn-light btn-sm">
                        Xem tất cả
                        <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($pendingRequests->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Mã yêu cầu</th>
                                    <th>Tiêu đề</th>
                                    <th>Quy trình</th>
                                    <th>Người yêu cầu</th>
                                    <th>Trạng thái</th>
                                    <th>Tiến độ</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingRequests as $request)
                                <tr>
                                    <td>
                                        <code>{{ $request->request_code }}</code>
                                    </td>
                                    <td>
                                        <strong>{{ $request->title }}</strong>
                                        <br>
                                        <small class="text-muted">{{ Str::limit($request->description, 50) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $request->workflow->name }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $request->requester_name }}
                                        <br>
                                        <small class="text-muted">{{ $request->requester_email }}</small>
                                    </td>
                                    <td>
                                        @if($request->status === 'submitted')
                                            <span class="badge bg-warning">Đã gửi</span>
                                        @elseif($request->status === 'in_review')
                                            <span class="badge bg-info">Đang xem xét</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $progress = $request->getProgressPercentage();
                                        @endphp
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ $progress }}%</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('approval.show', $request) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Không có yêu cầu nào đang chờ xử lý</h5>
                        <p class="text-muted">Tất cả yêu cầu đã được xử lý hoặc chưa có yêu cầu nào được gửi.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- My Tasks -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user-check me-2"></i>
                        Công việc của tôi
                    </h5>
                    <a href="{{ route('approval.my-tasks') }}" class="btn btn-light btn-sm">
                        Xem tất cả
                        <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($myTasks->count() > 0)
                    @foreach($myTasks as $task)
                    <div class="d-flex align-items-center mb-3 p-3 bg-light rounded">
                        <div class="flex-shrink-0">
                            @if($task->step_name === 'Cấu hình hệ thống')
                                <i class="fas fa-cogs fa-2x text-primary"></i>
                            @elseif($task->step_name === 'Quản lý người dùng')
                                <i class="fas fa-users fa-2x text-info"></i>
                            @elseif($task->step_name === 'Phê duyệt thay đổi')
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            @else
                                <i class="fas fa-tasks fa-2x text-secondary"></i>
                            @endif
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">{{ $task->step_name }}</h6>
                            <p class="mb-1 text-muted small">{{ $task->request->title }}</p>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                {{ $task->assigned_at ? $task->assigned_at->diffForHumans() : 'Chưa giao' }}
                            </small>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="{{ route('approval.show', $task->request) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                        <h6 class="text-muted">Không có công việc nào</h6>
                        <small class="text-muted">Bạn đã hoàn thành tất cả công việc!</small>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Thao tác nhanh
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if(auth()->user()->hasPermission('create_schools'))
                    <a href="{{ route('approval.create-school') }}" class="btn btn-outline-primary">
                        <i class="fas fa-graduation-cap me-2"></i>
                        Tạo Trường mới
                    </a>
                    @endif
                    @if(auth()->user()->hasPermission('propose_departments'))
                    <a href="{{ route('approval.create-department') }}" class="btn btn-outline-success">
                        <i class="fas fa-users me-2"></i>
                        Tạo Khoa mới
                    </a>
                    @endif
                    @if(auth()->user()->hasPermission('view_all_requests'))
                    <a href="{{ route('approval.requests') }}" class="btn btn-outline-info">
                        <i class="fas fa-search me-2"></i>
                        Tìm kiếm yêu cầu
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh stats every 30 seconds
    setInterval(function() {
        // In a real application, you would make an AJAX call here
        // to refresh the statistics
    }, 30000);
    
    // Add tooltips to progress bars
    const progressBars = document.querySelectorAll('.progress');
    progressBars.forEach(bar => {
        const percentage = bar.querySelector('.progress-bar').style.width;
        bar.setAttribute('title', `Tiến độ: ${percentage}`);
        new bootstrap.Tooltip(bar);
    });
});
</script>
@endpush
