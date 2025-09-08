@extends('layouts.app')

@section('title', 'Tạo Khoa mới')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Tạo Khoa mới</h1>
                <p class="text-muted">Tạo yêu cầu phê duyệt cho Khoa mới theo quy trình: {{ $workflow->name }}</p>
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
                    <i class="fas fa-users me-2"></i>
                    Thông tin Khoa mới
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('approval.store-department') }}" method="POST">
                    @csrf
                    
                    <!-- Thông tin cơ bản -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="university_id" class="form-label">Đại học <span class="text-danger">*</span></label>
                                <select class="form-select @error('university_id') is-invalid @enderror" id="university_id" name="university_id" required onchange="loadSchools()">
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
                                <label for="school_id" class="form-label">Trường <span class="text-danger">*</span></label>
                                <select class="form-select @error('school_id') is-invalid @enderror" id="school_id" name="school_id" required disabled>
                                    <option value="">Chọn Trường trước</option>
                                </select>
                                @error('school_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="code" class="form-label">Mã Khoa <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                       id="code" name="code" value="{{ old('code') }}" 
                                       placeholder="VD: HTTT, KHMT, CNPM" required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Tên Khoa <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" 
                                       placeholder="VD: Hệ thống thông tin" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="full_name" class="form-label">Tên đầy đủ</label>
                        <input type="text" class="form-control @error('full_name') is-invalid @enderror" 
                               id="full_name" name="full_name" value="{{ old('full_name') }}" 
                               placeholder="Khoa Hệ thống thông tin">
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Mô tả về Khoa...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Thông tin liên hệ -->
                    <h6 class="border-bottom pb-2 mb-3">Thông tin liên hệ</h6>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="head_name" class="form-label">Trưởng Khoa</label>
                                <input type="text" class="form-control @error('head_name') is-invalid @enderror" 
                                       id="head_name" name="head_name" value="{{ old('head_name') }}" 
                                       placeholder="Họ và tên Trưởng Khoa">
                                @error('head_name')
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
                                       placeholder="khoa@phenikaa.edu.vn">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="office_location" class="form-label">Vị trí văn phòng</label>
                                <input type="text" class="form-control @error('office_location') is-invalid @enderror" 
                                       id="office_location" name="office_location" value="{{ old('office_location') }}" 
                                       placeholder="Tòa A, tầng 3">
                                @error('office_location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="established_date" class="form-label">Ngày thành lập</label>
                                <input type="date" class="form-control @error('established_date') is-invalid @enderror" 
                                       id="established_date" name="established_date" value="{{ old('established_date') }}">
                                @error('established_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="student_count" class="form-label">Số sinh viên</label>
                                <input type="number" class="form-control @error('student_count') is-invalid @enderror" 
                                       id="student_count" name="student_count" value="{{ old('student_count', 0) }}" 
                                       min="0" placeholder="0">
                                @error('student_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="staff_count" class="form-label">Số cán bộ</label>
                                <input type="number" class="form-control @error('staff_count') is-invalid @enderror" 
                                       id="staff_count" name="staff_count" value="{{ old('staff_count', 0) }}" 
                                       min="0" placeholder="0">
                                @error('staff_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
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

        <!-- School Selection Info -->
        <div class="card mt-3" id="schoolInfo" style="display: none;">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Thông tin Trường được chọn
                </h5>
            </div>
            <div class="card-body" id="schoolInfoContent">
                <!-- School info will be loaded here -->
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
                    <strong>Lưu ý:</strong> Cần chọn Đại học trước để hiển thị danh sách Trường.
                </div>
                
                <h6>Các bước tiếp theo:</h6>
                <ol class="small">
                    <li>Chọn Đại học và Trường</li>
                    <li>Điền thông tin Khoa</li>
                    <li>Kiểm tra lại thông tin</li>
                    <li>Tạo yêu cầu</li>
                    <li>Submit yêu cầu để bắt đầu phê duyệt</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Load schools when university is selected
async function loadSchools() {
    const universitySelect = document.getElementById('university_id');
    const schoolSelect = document.getElementById('school_id');
    const schoolInfo = document.getElementById('schoolInfo');
    
    if (!universitySelect.value) {
        schoolSelect.innerHTML = '<option value="">Chọn Trường trước</option>';
        schoolSelect.disabled = true;
        schoolInfo.style.display = 'none';
        return;
    }
    
    try {
        schoolSelect.innerHTML = '<option value="">Đang tải...</option>';
        schoolSelect.disabled = true;
        
        const response = await fetch(`/approval/api/universities/${universitySelect.value}/schools`);
        const schools = await response.json();
        
        schoolSelect.innerHTML = '<option value="">Chọn Trường</option>';
        schools.forEach(school => {
            const option = document.createElement('option');
            option.value = school.id;
            option.textContent = `${school.name} (${school.code})`;
            schoolSelect.appendChild(option);
        });
        
        schoolSelect.disabled = false;
        
    } catch (error) {
        console.error('Error loading schools:', error);
        schoolSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
        alert('Có lỗi xảy ra khi tải danh sách Trường');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate full name based on name
    const nameInput = document.getElementById('name');
    const fullNameInput = document.getElementById('full_name');
    
    nameInput.addEventListener('input', function() {
        if (!fullNameInput.value) {
            fullNameInput.value = 'Khoa ' + this.value;
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
    
    // Load schools if university is pre-selected
    const universitySelect = document.getElementById('university_id');
    if (universitySelect.value) {
        loadSchools();
    }
    
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
    
    // Show school info when selected
    const schoolSelect = document.getElementById('school_id');
    schoolSelect.addEventListener('change', function() {
        const schoolInfo = document.getElementById('schoolInfo');
        const schoolInfoContent = document.getElementById('schoolInfoContent');
        
        if (this.value) {
            const selectedOption = this.options[this.selectedIndex];
            schoolInfoContent.innerHTML = `
                <p><strong>Trường được chọn:</strong></p>
                <p>${selectedOption.textContent}</p>
                <small class="text-muted">Khoa mới sẽ thuộc về Trường này.</small>
            `;
            schoolInfo.style.display = 'block';
        } else {
            schoolInfo.style.display = 'none';
        }
    });
});
</script>
@endpush

