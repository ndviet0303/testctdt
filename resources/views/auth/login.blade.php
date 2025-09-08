<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng nhập - Hệ thống Quản lý Phê duyệt</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --accent-color: #f093fb;
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --border-radius: 16px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            width: 100%;
            max-width: 500px;
            padding: 2rem;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 3rem;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h1 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: #6c757d;
        }

        .user-card {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }

        .user-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
        }

        .user-card.selected {
            border-color: var(--primary-color);
            background: rgba(102, 126, 234, 0.05);
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            margin-right: 1rem;
        }

        .user-details h6 {
            margin: 0;
            color: #2d3748;
            font-weight: 600;
        }

        .user-details .role {
            color: var(--primary-color);
            font-size: 0.9rem;
            font-weight: 500;
            text-transform: capitalize;
        }

        .user-details .department {
            color: #6c757d;
            font-size: 0.85rem;
        }

        .role-badge {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .role-system_admin { background: rgba(220, 53, 69, 0.2); color: #dc3545; }
        .role-director { background: rgba(255, 193, 7, 0.2); color: #ffc107; }
        .role-user_admin { background: rgba(13, 202, 240, 0.2); color: #0dcaf0; }
        .role-auditor { background: rgba(108, 117, 125, 0.2); color: #6c757d; }
        .role-reporter { background: rgba(25, 135, 84, 0.2); color: #198754; }
        .role-school_admin { background: rgba(102, 16, 242, 0.2); color: #6610f2; }
        .role-policy_admin { background: rgba(214, 51, 132, 0.2); color: #d63384; }
        .role-user { background: rgba(173, 181, 189, 0.2); color: #adb5bd; }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 25px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            transform: none;
            box-shadow: none;
        }

        .alert {
            border-radius: var(--border-radius);
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-card {
            animation: fadeIn 0.8s ease-out;
        }

        .user-card {
            position: relative;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1><i class="fas fa-university me-2"></i>Phenikaa University</h1>
                <p>Hệ thống Quản lý Phê duyệt - Demo</p>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <input type="hidden" id="selectedUserId" name="user_id" value="">
                
                <div class="mb-4">
                    <label class="form-label">Chọn tài khoản để đăng nhập:</label>
                    <div class="users-list">
                        @foreach($users as $user)
                        <div class="user-card" onclick="selectUser({{ $user->id }})">
                            <div class="role-badge role-{{ $user->role }}">
                                {{ str_replace('_', ' ', $user->role) }}
                            </div>
                            <div class="user-info">
                                <div class="user-avatar">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div class="user-details">
                                    <h6>{{ $user->name }}</h6>
                                    <div class="role">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</div>
                                    <div class="department">{{ $user->department }}</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" id="loginBtn" disabled>
                    <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                </button>
            </form>

            <div class="text-center mt-4">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Chọn một tài khoản để trải nghiệm hệ thống với vai trò tương ứng
                </small>
            </div>

            <div class="mt-4">
                <div class="accordion" id="rolesAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#rolesCollapse">
                                <i class="fas fa-users me-2"></i>Thông tin vai trò
                            </button>
                        </h2>
                        <div id="rolesCollapse" class="accordion-collapse collapse" data-bs-parent="#rolesAccordion">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Vai trò quản lý:</h6>
                                        <ul class="list-unstyled small">
                                            <li><strong>System Admin:</strong> Quản trị hệ thống</li>
                                            <li><strong>Director:</strong> Phê duyệt cuối cùng</li>
                                            <li><strong>User Admin:</strong> Quản lý người dùng</li>
                                            <li><strong>Auditor:</strong> Kiểm toán, audit</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Vai trò nghiệp vụ:</h6>
                                        <ul class="list-unstyled small">
                                            <li><strong>School Admin:</strong> Quản lý Trường</li>
                                            <li><strong>Policy Admin:</strong> Quản lý chính sách</li>
                                            <li><strong>Reporter:</strong> Tạo báo cáo</li>
                                            <li><strong>User:</strong> Người dùng thông thường</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let selectedUserId = null;

        function selectUser(userId) {
            // Remove previous selection
            document.querySelectorAll('.user-card').forEach(card => {
                card.classList.remove('selected');
            });

            // Select current user
            event.target.closest('.user-card').classList.add('selected');
            selectedUserId = userId;
            
            // Update hidden input and enable button
            document.getElementById('selectedUserId').value = userId;
            document.getElementById('loginBtn').disabled = false;
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });

            // Form validation
            document.querySelector('form').addEventListener('submit', function(e) {
                if (!selectedUserId) {
                    e.preventDefault();
                    alert('Vui lòng chọn một tài khoản để đăng nhập!');
                }
            });
        });
    </script>
</body>
</html>

