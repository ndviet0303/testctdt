@extends('layouts.app')

@section('title', 'Tạo yêu cầu mới')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-plus-circle me-2"></i>
                    Tạo yêu cầu phê duyệt mới
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ route('approval.store') }}" method="POST">
                    @csrf
                    
                    <!-- Chọn loại workflow -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="workflow_id" class="form-label">
                                <i class="fas fa-cogs me-1"></i>
                                Loại yêu cầu <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('workflow_id') is-invalid @enderror" 
                                    id="workflow_id" name="workflow_id" required>
                                <option value="">-- Chọn loại yêu cầu --</option>
                                @foreach($workflows as $workflow)
                                <option value="{{ $workflow->id }}" 
                                        data-entity-type="{{ $workflow->entity_type }}"
                                        {{ old('workflow_id') == $workflow->id ? 'selected' : '' }}>
                                    {{ $workflow->name }}
                                    @if($workflow->description)
                                    - {{ $workflow->description }}
                                    @endif
                                </option>
                                @endforeach
                            </select>
                            @error('workflow_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Thông tin cơ bản -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label for="title" class="form-label">
                                <i class="fas fa-heading me-1"></i>
                                Tiêu đề yêu cầu <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" 
                                   placeholder="Nhập tiêu đề mô tả ngắn gọn yêu cầu" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left me-1"></i>
                                Mô tả chi tiết
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Mô tả chi tiết về yêu cầu, lý do và các thông tin cần thiết khác">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Thông tin người yêu cầu -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="fas fa-user me-2"></i>
                                Thông tin người yêu cầu
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="requester_name" class="form-label">
                                        Họ và tên <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('requester_name') is-invalid @enderror" 
                                           id="requester_name" name="requester_name" value="{{ old('requester_name', auth()->user()->name ?? '') }}" required>
                                    @error('requester_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="requester_email" class="form-label">
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control @error('requester_email') is-invalid @enderror" 
                                           id="requester_email" name="requester_email" value="{{ old('requester_email', auth()->user()->email ?? '') }}" required>
                                    @error('requester_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="requester_department" class="form-label">
                                        Đơn vị/Phòng ban
                                    </label>
                                    <input type="text" class="form-control @error('requester_department') is-invalid @enderror" 
                                           id="requester_department" name="requester_department" value="{{ old('requester_department', auth()->user()->department ?? '') }}">
                                    @error('requester_department')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dữ liệu thực thể (sẽ được hiển thị dựa trên workflow được chọn) -->
                    <div class="card mb-4" id="entity-data-section" style="display: none;">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="fas fa-database me-2"></i>
                                Thông tin chi tiết
                            </h6>
                        </div>
                        <div class="card-body" id="entity-data-content">
                            <!-- Content will be loaded dynamically based on workflow selection -->
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('approval.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Quay lại
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>
                                    Tạo yêu cầu
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const workflowSelect = document.getElementById('workflow_id');
    const entityDataSection = document.getElementById('entity-data-section');
    const entityDataContent = document.getElementById('entity-data-content');
    
    workflowSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const entityType = selectedOption.getAttribute('data-entity-type');
        
        if (entityType && this.value) {
            entityDataSection.style.display = 'block';
            loadEntityDataForm(entityType);
        } else {
            entityDataSection.style.display = 'none';
            entityDataContent.innerHTML = '';
        }
    });
    
    function loadEntityDataForm(entityType) {
        // Simple form generation based on entity type
        let formHtml = '';
        
        switch(entityType) {
            case 'school':
                formHtml = `
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Tên trường <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="entity_data[name]" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mã trường <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="entity_data[code]" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label class="form-label">Tên đầy đủ</label>
                            <input type="text" class="form-control" name="entity_data[full_name]">
                        </div>
                    </div>`;
                break;
                
            case 'department':
                formHtml = `
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Tên khoa <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="entity_data[name]" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mã khoa <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="entity_data[code]" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label class="form-label">Tên đầy đủ</label>
                            <input type="text" class="form-control" name="entity_data[full_name]">
                        </div>
                    </div>`;
                break;
                
            default:
                formHtml = `
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Vui lòng điền thông tin bổ sung vào phần mô tả chi tiết ở trên.
                    </div>`;
        }
        
        entityDataContent.innerHTML = formHtml;
    }
    
    // Trigger change event if there's a pre-selected value
    if (workflowSelect.value) {
        workflowSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
@endsection
