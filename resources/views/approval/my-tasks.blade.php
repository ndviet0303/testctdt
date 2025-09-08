@extends('layouts.app')

@section('title', 'Công việc của tôi')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-user-check me-2"></i>
                    Công việc cần phê duyệt của tôi
                </h4>
            </div>
            <div class="card-body">
                @if($pendingSteps->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Mã yêu cầu</th>
                                    <th>Tiêu đề</th>
                                    <th>Loại workflow</th>
                                    <th>Bước hiện tại</th>
                                    <th>Được giao</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingSteps as $step)
                                <tr>
                                    <td>
                                        <code>{{ $step->request->request_code }}</code>
                                    </td>
                                    <td>
                                        <strong>{{ $step->request->title }}</strong>
                                        @if($step->request->description)
                                        <br><small class="text-muted">{{ Str::limit($step->request->description, 80) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $step->request->workflow->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="badge bg-warning me-2">
                                                Bước {{ $step->step_order }}
                                            </div>
                                            <div>
                                                <strong>{{ $step->step_name }}</strong>
                                                @if($step->description)
                                                <br><small class="text-muted">{{ $step->description }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $step->assigned_at ? $step->assigned_at->format('d/m/Y H:i') : 'Chưa có' }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('approval.show', $step->request) }}" 
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye me-1"></i>
                                                Xem chi tiết
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $pendingSteps->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="text-muted">Không có công việc nào cần phê duyệt</h5>
                        <p class="text-muted">Bạn đã hoàn thành tất cả các công việc được giao hoặc chưa có công việc mới.</p>
                        <a href="{{ route('approval.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Quay lại Dashboard
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($pendingSteps->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Hướng dẫn
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="fas fa-eye text-primary me-2"></i>Xem chi tiết</h6>
                        <p class="text-muted small">
                            Nhấp vào "Xem chi tiết" để xem đầy đủ thông tin yêu cầu và thực hiện phê duyệt hoặc từ chối.
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-clock text-warning me-2"></i>Thời gian xử lý</h6>
                        <p class="text-muted small">
                            Các yêu cầu được sắp xếp theo thời gian giao công việc. Ưu tiên xử lý các yêu cầu được giao sớm nhất.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
