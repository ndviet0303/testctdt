<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo Business Logic - Quy trình Phê duyệt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --accent-color: #f093fb;
            --success-color: #4facfe;
            --warning-color: #f6d55c;
            --danger-color: #ff6b6b;
            --dark-color: #2d3748;
            --light-color: #f7fafc;
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
            color: var(--dark-color);
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: none;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color) !important;
        }

        .main-container {
            padding: 2rem 0;
            min-height: calc(100vh - 100px);
        }

        .hero-section {
            text-align: center;
            color: white;
            margin-bottom: 3rem;
            animation: fadeInUp 0.8s ease-out;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .hero-subtitle {
            font-size: 1.2rem;
            font-weight: 300;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .workflow-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
            overflow: hidden;
            margin-bottom: 2rem;
            animation: fadeInUp 0.8s ease-out;
        }

        .workflow-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
        }

        .workflow-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .workflow-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .workflow-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin-right: 1rem;
        }

        .workflow-info h4 {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.25rem;
        }

        .workflow-code {
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .workflow-steps {
            margin-top: 1rem;
        }

        .step-item {
            background: rgba(102, 126, 234, 0.1);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            border-left: 4px solid var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .step-number {
            background: var(--primary-color);
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 600;
            margin-right: 1rem;
        }

        .step-content {
            flex: 1;
        }

        .step-name {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.25rem;
        }

        .step-description {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .step-timeout {
            font-size: 0.8rem;
            color: var(--warning-color);
            background: rgba(246, 213, 92, 0.2);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 25px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .entity-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .entity-university { background: rgba(102, 126, 234, 0.2); color: var(--primary-color); }
        .entity-school { background: rgba(240, 147, 251, 0.2); color: #c53030; }
        .entity-department { background: rgba(79, 172, 254, 0.2); color: #2b6cb0; }

        .action-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            margin-left: 0.5rem;
        }

        .action-create { background: rgba(72, 187, 120, 0.2); color: #2f855a; }
        .action-update { background: rgba(246, 213, 92, 0.2); color: #d69e2e; }
        .action-activate { background: rgba(79, 172, 254, 0.2); color: #2b6cb0; }
        .action-delete { background: rgba(255, 107, 107, 0.2); color: #c53030; }

        .recent-requests {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--card-shadow);
            margin-top: 2rem;
        }

        .request-item {
            background: white;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--accent-color);
            transition: all 0.3s ease;
        }

        .request-item:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .request-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .request-title {
            font-weight: 600;
            color: var(--dark-color);
        }

        .request-code {
            font-size: 0.8rem;
            color: #6c757d;
            font-family: monospace;
            background: #f8f9fa;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-draft { background: rgba(108, 117, 125, 0.2); color: #495057; }
        .status-submitted { background: rgba(246, 213, 92, 0.2); color: #d69e2e; }
        .status-in-review { background: rgba(79, 172, 254, 0.2); color: #2b6cb0; }
        .status-approved { background: rgba(72, 187, 120, 0.2); color: #2f855a; }
        .status-rejected { background: rgba(255, 107, 107, 0.2); color: #c53030; }

        .modal-content {
            border-radius: var(--border-radius);
            border: none;
            box-shadow: var(--card-shadow);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-cogs me-2"></i>
                Business Logic Demo
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-home me-1"></i>
                    Dashboard
                </a>
                <a class="nav-link" href="{{ route('organization.index') }}">
                    <i class="fas fa-sitemap me-1"></i>
                    Cây tổ chức
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-container">
        <div class="container">
            <!-- Hero Section -->
            <div class="hero-section">
                <h1 class="hero-title">Quy trình Phê duyệt</h1>
                <p class="hero-subtitle">Demo Business Logic theo sơ đồ quy trình nghiệp vụ</p>
            </div>

            <!-- Workflows Section -->
            <div class="row">
                <div class="col-12">
                    <h2 class="text-white mb-4">
                        <i class="fas fa-project-diagram me-2"></i>
                        Các Quy trình Phê duyệt
                    </h2>
                </div>
            </div>

            <div class="row">
                @foreach($workflows as $index => $workflow)
                <div class="col-lg-6 col-xl-4">
                    <div class="workflow-card" style="animation-delay: {{ 0.1 + ($index * 0.1) }}s">
                        <div class="workflow-header">
                            <div class="workflow-icon">
                                @switch($workflow->entity_type)
                                    @case('university')
                                        <i class="fas fa-university"></i>
                                        @break
                                    @case('school')
                                        <i class="fas fa-graduation-cap"></i>
                                        @break
                                    @case('department')
                                        <i class="fas fa-users"></i>
                                        @break
                                @endswitch
                            </div>
                            <div class="workflow-info">
                                <h4>{{ $workflow->name }}</h4>
                                <div class="workflow-code">{{ $workflow->code }}</div>
                            </div>
                        </div>

                        <p class="text-muted mb-3">{{ $workflow->description }}</p>

                        <div class="d-flex align-items-center mb-3">
                            <span class="entity-badge entity-{{ $workflow->entity_type }}">
                                {{ ucfirst($workflow->entity_type) }}
                            </span>
                            <span class="action-badge action-{{ $workflow->action_type }}">
                                {{ ucfirst($workflow->action_type) }}
                            </span>
                        </div>

                        <div class="workflow-steps">
                            <h6 class="mb-3">
                                <i class="fas fa-list-ol me-2"></i>
                                Các bước ({{ count($workflow->workflow_steps) }})
                            </h6>
                            
                            @foreach($workflow->workflow_steps as $step)
                            <div class="step-item">
                                <div class="step-number">{{ $step['order'] }}</div>
                                <div class="step-content">
                                    <div class="step-name">{{ $step['name'] }}</div>
                                    <div class="step-description">{{ $step['description'] }}</div>
                                </div>
                                @if(isset($step['timeout_hours']))
                                <div class="step-timeout">{{ $step['timeout_hours'] }}h</div>
                                @endif
                            </div>
                            @endforeach
                        </div>

                        <div class="text-center mt-3">
                            <button class="btn btn-primary" onclick="openRequestModal({{ $workflow->id }}, '{{ $workflow->name }}')">
                                <i class="fas fa-plus me-2"></i>
                                Tạo yêu cầu
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Recent Requests Section -->
            @if($recentRequests->count() > 0)
            <div class="recent-requests">
                <h3 class="mb-4">
                    <i class="fas fa-clock me-2"></i>
                    Yêu cầu gần đây ({{ $recentRequests->count() }})
                </h3>

                @foreach($recentRequests as $request)
                <div class="request-item">
                    <div class="request-header">
                        <div>
                            <div class="request-title">{{ $request->title }}</div>
                            <div class="request-code">{{ $request->request_code }}</div>
                        </div>
                        <div>
                            <span class="status-badge status-{{ $request->status }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">
                                <i class="fas fa-user me-1"></i>
                                {{ $request->requester_name }}
                            </small>
                            <small class="text-muted ms-3">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $request->created_at->diffForHumans() }}
                            </small>
                        </div>
                        <div>
                            <small class="text-muted">
                                Quy trình: {{ $request->workflow->name }}
                            </small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <!-- Create Request Modal -->
    <div class="modal fade" id="requestModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i>
                        Tạo yêu cầu mới
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="requestForm">
                        <div class="mb-3">
                            <label for="title" class="form-label">Tiêu đề yêu cầu</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="requester_name" class="form-label">Tên người yêu cầu</label>
                                    <input type="text" class="form-control" id="requester_name" name="requester_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="requester_email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="requester_email" name="requester_email" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="requester_department" class="form-label">Bộ phận</label>
                            <input type="text" class="form-control" id="requester_department" name="requester_department">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Dữ liệu đối tượng (JSON)</label>
                            <textarea class="form-control" id="entity_data" name="entity_data" rows="5" placeholder='{"name": "Tên trường/khoa", "code": "CODE", ...}'></textarea>
                            <div class="form-text">Nhập dữ liệu JSON cho đối tượng cần tạo/cập nhật</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" onclick="submitRequest()">
                        <i class="fas fa-paper-plane me-2"></i>
                        Tạo yêu cầu
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentWorkflowId = null;
        
        function openRequestModal(workflowId, workflowName) {
            currentWorkflowId = workflowId;
            document.querySelector('.modal-title').innerHTML = 
                `<i class="fas fa-plus me-2"></i>Tạo yêu cầu: ${workflowName}`;
            
            // Reset form
            document.getElementById('requestForm').reset();
            
            // Show modal
            new bootstrap.Modal(document.getElementById('requestModal')).show();
        }
        
        async function submitRequest() {
            if (!currentWorkflowId) return;
            
            const form = document.getElementById('requestForm');
            const formData = new FormData(form);
            
            // Parse JSON data
            let entityData;
            try {
                entityData = JSON.parse(formData.get('entity_data') || '{}');
            } catch (e) {
                alert('Dữ liệu JSON không hợp lệ');
                return;
            }
            
            const data = {
                title: formData.get('title'),
                description: formData.get('description'),
                requester_name: formData.get('requester_name'),
                requester_email: formData.get('requester_email'),
                requester_department: formData.get('requester_department'),
                entity_data: entityData
            };
            
            try {
                const response = await fetch(`/api/workflows/${currentWorkflowId}/create-request`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert('Yêu cầu đã được tạo thành công!');
                    bootstrap.Modal.getInstance(document.getElementById('requestModal')).hide();
                    location.reload(); // Reload to show new request
                } else {
                    alert('Có lỗi xảy ra: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi gửi yêu cầu');
            }
        }

        // Add CSRF token to all requests
        document.addEventListener('DOMContentLoaded', function() {
            // Add meta tag for CSRF token if not exists
            if (!document.querySelector('meta[name="csrf-token"]')) {
                const meta = document.createElement('meta');
                meta.name = 'csrf-token';
                meta.content = '{{ csrf_token() }}';
                document.head.appendChild(meta);
            }

            // Animate cards on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.workflow-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'all 0.6s ease-out';
                observer.observe(card);
            });
        });
    </script>
</body>
</html>

