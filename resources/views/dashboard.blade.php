@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Hero Section -->
    <div class="hero-section mb-5">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="hero-content">
                    <h1 class="hero-title">{{ $university ? $university->name : 'Đại học Phenikaa' }}</h1>
                    <p class="hero-subtitle">{{ $university ? $university->full_name : 'Đại học Phenikaa - Phenikaa University' }}</p>
                    <p class="hero-description">{{ $university ? $university->description : 'Đại học Phenikaa là một trường đại học tư thục hàng đầu tại Việt Nam, được thành lập năm 2007.' }}</p>
                    
                    @auth
                        <div class="user-welcome mt-3">
                            <div class="alert alert-info border-0">
                                <i class="fas fa-user-circle me-2"></i>
                                Chào mừng, <strong>{{ auth()->user()->name }}</strong>! 
                                <span class="badge bg-primary ms-2">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</span>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
            <div class="col-lg-4">
                <div class="hero-stats">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-number">{{ $stats['total_schools'] }}</div>
                            <div class="stat-label">Trường</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-number">{{ $stats['total_departments'] }}</div>
                            <div class="stat-label">Khoa</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-number">{{ number_format($stats['total_students']) }}</div>
                            <div class="stat-label">Sinh viên</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions mb-5">
        <div class="row">
            <div class="col-12">
                <h3 class="section-title">
                    <i class="fas fa-bolt me-2"></i>
                    Truy cập nhanh
                </h3>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <a href="{{ route('organization.index') }}" class="action-card">
                    <div class="action-icon bg-primary">
                        <i class="fas fa-sitemap"></i>
                    </div>
                    <div class="action-content">
                        <h5>Cây tổ chức</h5>
                        <p>Xem cấu trúc tổ chức của trường</p>
                    </div>
                    <div class="action-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </a>
            </div>
            
            @auth
                @if(auth()->user()->hasPermission('view_requests'))
                <div class="col-lg-4 col-md-6">
                    <a href="{{ route('approval.index') }}" class="action-card">
                        <div class="action-icon bg-success">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <div class="action-content">
                            <h5>Quản lý Phê duyệt</h5>
                            <p>Xử lý các yêu cầu phê duyệt</p>
                        </div>
                        <div class="action-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>
                </div>
                @endif

                @if(auth()->user()->hasPermission('create_schools'))
                <div class="col-lg-4 col-md-6">
                    <a href="{{ route('approval.create-school') }}" class="action-card">
                        <div class="action-icon bg-info">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <div class="action-content">
                            <h5>Tạo Trường mới</h5>
                            <p>Tạo yêu cầu thành lập trường mới</p>
                        </div>
                        <div class="action-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>
                </div>
                @endif
            @else
                <div class="col-lg-4 col-md-6">
                    <a href="{{ route('login') }}" class="action-card">
                        <div class="action-icon bg-warning">
                            <i class="fas fa-sign-in-alt"></i>
                        </div>
                        <div class="action-content">
                            <h5>Đăng nhập</h5>
                            <p>Đăng nhập để sử dụng đầy đủ tính năng</p>
                        </div>
                        <div class="action-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>
                </div>
            @endauth
        </div>
    </div>

    <!-- Schools Overview -->
    <div class="schools-overview">
        <div class="row">
            <div class="col-12">
                <h3 class="section-title">
                    <i class="fas fa-building me-2"></i>
                    Các Trường
                </h3>
            </div>
        </div>
        <div class="row g-4">
            @forelse($schools as $school)
                <div class="col-lg-6 col-xl-4">
                    <div class="school-card">
                        <div class="school-header">
                            <div class="school-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="school-info">
                                <h5 class="school-name">{{ $school->name }}</h5>
                                <p class="school-code">{{ $school->code }}</p>
                            </div>
                        </div>
                        <div class="school-body">
                            <p class="school-description">{{ $school->description }}</p>
                        </div>
                        <div class="school-footer">
                            <div class="school-stats">
                                <div class="stat">
                                    <span class="stat-value">{{ $school->departments->count() }}</span>
                                    <span class="stat-label">Khoa</span>
                                </div>
                                <div class="stat">
                                    <span class="stat-value">{{ number_format($school->departments->sum('student_count')) }}</span>
                                    <span class="stat-label">Sinh viên</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <h4>Chưa có Trường nào</h4>
                        <p>Hệ thống chưa có dữ liệu về các Trường. Vui lòng liên hệ quản trị viên.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
.hero-section {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    border-radius: 20px;
    padding: 3rem 2rem;
    margin: 2rem 0;
}

.hero-title {
    font-size: 3rem;
    font-weight: 700;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 1rem;
}

.hero-subtitle {
    font-size: 1.5rem;
    color: #6c757d;
    margin-bottom: 1rem;
}

.hero-description {
    font-size: 1.1rem;
    color: #495057;
    line-height: 1.6;
}

.hero-stats {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.stat-card {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
}

.stat-icon i {
    color: white;
    font-size: 1.5rem;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #2d3748;
    line-height: 1;
}

.stat-label {
    color: #6c757d;
    font-size: 0.9rem;
    margin-top: 0.25rem;
}

.section-title {
    font-size: 1.8rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 2rem;
}

.action-card {
    display: block;
    background: white;
    border-radius: 16px;
    padding: 2rem;
    text-decoration: none;
    color: inherit;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.action-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    color: inherit;
    text-decoration: none;
}

.action-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    transition: left 0.5s ease;
}

.action-card:hover::before {
    left: 100%;
}

.action-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
}

.action-icon i {
    color: white;
    font-size: 1.8rem;
}

.action-content h5 {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #2d3748;
}

.action-content p {
    color: #6c757d;
    margin-bottom: 1rem;
}

.action-arrow {
    position: absolute;
    top: 1.5rem;
    right: 1.5rem;
    color: #dee2e6;
    font-size: 1.2rem;
    transition: all 0.3s ease;
}

.action-card:hover .action-arrow {
    color: #667eea;
    transform: translateX(5px);
}

.school-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.school-card:hover {
    transform: translateY(-5px);
}

.school-header {
    display: flex;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #f1f3f4;
}

.school-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
}

.school-icon i {
    color: white;
    font-size: 1.2rem;
}

.school-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.25rem;
}

.school-code {
    color: #6c757d;
    font-size: 0.9rem;
    margin: 0;
}

.school-body {
    padding: 1rem 1.5rem;
}

.school-description {
    color: #495057;
    font-size: 0.9rem;
    line-height: 1.5;
    margin: 0;
}

.school-footer {
    padding: 1rem 1.5rem;
    background: #f8f9fa;
}

.school-stats {
    display: flex;
    justify-content: space-around;
}

.stat {
    text-align: center;
}

.stat-value {
    display: block;
    font-size: 1.3rem;
    font-weight: 700;
    color: #2d3748;
}

.stat-label {
    font-size: 0.8rem;
    color: #6c757d;
    margin-top: 0.25rem;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.empty-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #dee2e6 0%, #adb5bd 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
}

.empty-icon i {
    color: white;
    font-size: 2rem;
}

.empty-state h4 {
    color: #2d3748;
    margin-bottom: 1rem;
}

.empty-state p {
    color: #6c757d;
    margin: 0;
}

.user-welcome .alert {
    background: linear-gradient(135deg, rgba(13, 110, 253, 0.1) 0%, rgba(25, 135, 84, 0.1) 100%);
    border: 1px solid rgba(13, 110, 253, 0.2);
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-subtitle {
        font-size: 1.2rem;
    }
    
    .hero-section {
        padding: 2rem 1rem;
    }
    
    .action-card {
        padding: 1.5rem;
    }
}
</style>
@endsection

