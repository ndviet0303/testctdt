@extends('layouts.app')

@section('title', 'Tạo Trường mới')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Tạo Trường mới</h1>
                <p class="text-muted">Tạo yêu cầu phê duyệt cho Trường mới theo quy trình: {{ $workflow->name }}</p>
            </div>
            <a href="{{ route('approval.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-graduation-cap me-2"></i>
                    Thông tin Trường mới
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('approval.store-school') }}" method="POST">
                    @csrf
                    
                    <!-- Thông tin cơ bản -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="university_id" class="form-label">Đại học <span class="text-danger">*</span></label>
                                <select class="form-select @error('university_id') is-invalid @enderror" id="university_id" name="university_id" required>
                                    <option value="">Chọn Đại học</option>
                                    @foreach($universities as $university)
                                        <option value="{{ $university->id }}" {{ old('university_id') == $university->id ? 'selected' : '' }}>
                                            {{ $university->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('university_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="code" class="form-label">Mã Trường <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                       id="code" name="code" value="{{ old('code') }}" 
                                       placeholder="VD: CNTT, KT, KD" required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Tên Trường <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" 
                                       placeholder="VD: Công nghệ thông tin" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Tên đầy đủ</label>
                                <input type="text" class="form-control @error('full_name') is-invalid @enderror" 
                                       id="full_name" name="full_name" value="{{ old('full_name') }}" 
                                       placeholder="Trường Công nghệ thông tin">
                                @error('full_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Mô tả về Trường...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Thông tin liên hệ -->
                    <h6 class="border-bottom pb-2 mb-3">Thông tin liên hệ</h6>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="dean_name" class="form-label">Trưởng Trường</label>
                                <input type="text" class="form-control @error('dean_name') is-invalid @enderror" 
                                       id="dean_name" name="dean_name" value="{{ old('dean_name') }}" 
                                       placeholder="Họ và tên Trưởng Trường">
                                @error('dean_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Điện thoại</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}" 
                                       placeholder="0123456789">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" 
                                       placeholder="truong@phenikaa.edu.vn">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="established_date" class="form-label">Ngày thành lập</label>
                                <input type="date" class="form-control @error('established_date') is-invalid @enderror" 
                                       id="established_date" name="established_date" value="{{ old('established_date') }}">
                                @error('established_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Địa chỉ</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="2" 
                                  placeholder="Địa chỉ của Trường...">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Thông tin người yêu cầu -->
                    <h6 class="border-bottom pb-2 mb-3 mt-4">Thông tin người yêu cầu</h6>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="requester_name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('requester_name') is-invalid @enderror" 
                                       id="requester_name" name="requester_name" value="{{ old('requester_name') }}" 
                                       placeholder="Nguyen Van A" required>
                                @error('requester_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="requester_email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('requester_email') is-invalid @enderror" 
                                       id="requester_email" name="requester_email" value="{{ old('requester_email') }}" 
                                       placeholder="nguyen.van.a@phenikaa.edu.vn" required>
                                @error('requester_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="requester_department" class="form-label">Bộ phận</label>
                        <input type="text" class="form-control @error('requester_department') is-invalid @enderror" 
                               id="requester_department" name="requester_department" value="{{ old('requester_department') }}" 
                               placeholder="Phòng Đào tạo">
                        @error('requester_department')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-outline-secondary me-2" onclick="history.back()">
                            <i class="fas fa-times me-2"></i>Hủy
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>Tạo yêu cầu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Workflow Info -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-project-diagram me-2"></i>
                    Quy trình phê duyệt
                </h5>
            </div>
            <div class="card-body">
                <h6>{{ $workflow->name }}</h6>
                <p class="text-muted small">{{ $workflow->description }}</p>
                
                <div class="timeline">
                    @foreach($workflow->workflow_steps as $index => $step)
                    <div class="timeline-item">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <span class="badge bg-primary rounded-pill">{{ $step['order'] }}</span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{{ $step['name'] }}</h6>
                                <p class="text-muted small mb-1">{{ $step['description'] }}</p>
                                <div class="d-flex align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i>{{ $step['approver_role'] }}
                                    </small>
                                    @if(isset($step['timeout_hours']))
                                    <small class="text-warning ms-2">
                                        <i class="fas fa-clock me-1"></i>{{ $step['timeout_hours'] }}h
                                    </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Help -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-question-circle me-2"></i>
                    Trợ giúp
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Lưu ý:</strong> Sau khi tạo yêu cầu, bạn cần submit để bắt đầu quy trình phê duyệt.
                </div>
                
                <h6>Các bước tiếp theo:</h6>
                <ol class="small">
                    <li>Điền đầy đủ thông tin Trường</li>
                    <li>Kiểm tra lại thông tin</li>
                    <li>Tạo yêu cầu</li>
                    <li>Submit yêu cầu để bắt đầu phê duyệt</li>
                    <li>Theo dõi tiến độ phê duyệt</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate full name based on name
    const nameInput = document.getElementById('name');
    const fullNameInput = document.getElementById('full_name');
    
    nameInput.addEventListener('input', function() {
        if (!fullNameInput.value) {
            fullNameInput.value = 'Trường ' + this.value;
        }
    });
    
    // Auto-generate email based on code
    const codeInput = document.getElementById('code');
    const emailInput = document.getElementById('email');
    
    codeInput.addEventListener('input', function() {
        if (!emailInput.value) {
            const code = this.value.toLowerCase().replace(/\s+/g, '');
            emailInput.value = code + '@phenikaa.edu.vn';
        }
    });
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Vui lòng điền đầy đủ các trường bắt buộc!');
        }
    });
});
</script>
@endpush

