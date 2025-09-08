@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="fas fa-sitemap me-3"></i>Cây Tổ chức</h1>
                <div>
                    <button id="expandAll" class="btn btn-outline-success btn-sm me-2">
                        <i class="fas fa-expand-arrows-alt me-1"></i>Mở rộng tất cả
                    </button>
                    <button id="collapseAll" class="btn btn-outline-warning btn-sm me-2">
                        <i class="fas fa-compress-arrows-alt me-1"></i>Thu gọn tất cả
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Dashboard
                    </a>
                </div>
            </div>

            @if($universities->isEmpty())
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i>
                    Chưa có dữ liệu tổ chức. Vui lòng chạy seed để tạo dữ liệu mẫu.
                </div>
            @else
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card shadow-sm">
                            <div class="card-header bg-gradient-primary text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-tree me-2"></i>Cây Tổ chức
                                </h5>
                            </div>
                            <div class="card-body p-0">
                                <div id="orgTree" class="org-tree-container">
                                    @foreach($universities as $university)
                                        <div class="tree-node university-node" data-type="university" data-id="{{ $university->id }}">
                                            <div class="node-content">
                                                <i class="fas fa-university node-icon"></i>
                                                <span class="node-title">{{ $university->name }}</span>
                                                <span class="node-code">({{ $university->code }})</span>
                                                <button class="btn btn-sm btn-link toggle-btn">
                                                    <i class="fas fa-chevron-down"></i>
                                                </button>
                                            </div>
                                            
                                            @if($university->schools->isNotEmpty())
                                                <div class="tree-children">
                                                    @foreach($university->schools as $school)
                                                        <div class="tree-node school-node" data-type="school" data-id="{{ $school->id }}">
                                                            <div class="node-content">
                                                                <i class="fas fa-building node-icon"></i>
                                                                <span class="node-title">{{ $school->name }}</span>
                                                                <span class="node-code">({{ $school->code }})</span>
                                                                @if($school->departments->isNotEmpty())
                                                                    <button class="btn btn-sm btn-link toggle-btn">
                                                                        <i class="fas fa-chevron-down"></i>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                            
                                                            @if($school->departments->isNotEmpty())
                                                                <div class="tree-children">
                                                                    @foreach($school->departments as $department)
                                                                        <div class="tree-node department-node" data-type="department" data-id="{{ $department->id }}">
                                                                            <div class="node-content">
                                                                                <i class="fas fa-users node-icon"></i>
                                                                                <span class="node-title">{{ $department->name }}</span>
                                                                                <span class="node-code">({{ $department->code }})</span>
                                                                                <span class="badge bg-info ms-2">{{ $department->student_count }} SV</span>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-gradient-info text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Chi tiết
                                </h5>
                            </div>
                            <div class="card-body">
                                <div id="nodeDetails">
                                    <div class="text-center text-muted">
                                        <i class="fas fa-mouse-pointer fa-2x mb-3"></i>
                                        <p>Nhấp vào một nút trong cây để xem chi tiết</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card shadow-sm mt-4">
                            <div class="card-header bg-gradient-success text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-chart-bar me-2"></i>Thống kê
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-6 mb-3">
                                        <div class="stat-item">
                                            <div class="stat-number text-primary">{{ $universities->count() }}</div>
                                            <div class="stat-label">Trường ĐH</div>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="stat-item">
                                            <div class="stat-number text-success">{{ $universities->sum(function($u) { return $u->schools->count(); }) }}</div>
                                            <div class="stat-label">Trường</div>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="stat-item">
                                            <div class="stat-number text-info">{{ $universities->sum(function($u) { return $u->departments->count(); }) }}</div>
                                            <div class="stat-label">Khoa</div>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="stat-item">
                                            <div class="stat-number text-warning">{{ $universities->sum(function($u) { return $u->departments->sum('student_count'); }) }}</div>
                                            <div class="stat-label">Sinh viên</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.org-tree-container {
    padding: 20px;
    font-family: 'Inter', sans-serif;
}

.tree-node {
    margin: 8px 0;
    position: relative;
}

.tree-node:before {
    content: '';
    position: absolute;
    left: -20px;
    top: 25px;
    width: 15px;
    height: 1px;
    background: #dee2e6;
}

.tree-node:not(:last-child):after {
    content: '';
    position: absolute;
    left: -20px;
    top: 25px;
    width: 1px;
    height: calc(100% + 8px);
    background: #dee2e6;
}

.tree-children {
    margin-left: 30px;
    position: relative;
}

.tree-children:before {
    content: '';
    position: absolute;
    left: -20px;
    top: 0;
    width: 1px;
    height: 100%;
    background: #dee2e6;
}

.node-content {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #ffffff;
    border: 1px solid #e9ecef;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.node-content:hover {
    background: #f8f9fa;
    border-color: #dee2e6;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transform: translateY(-1px);
}

.university-node .node-content {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-weight: 600;
    font-size: 1.1em;
}

.university-node .node-content:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
}

.school-node .node-content {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    font-weight: 500;
}

.school-node .node-content:hover {
    background: linear-gradient(135deg, #ee82f0 0%, #f3455a 100%);
    transform: translateY(-1px);
}

.department-node .node-content {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
}

.department-node .node-content:hover {
    background: linear-gradient(135deg, #3d9bfd 0%, #00e0f0 100%);
    transform: translateY(-1px);
}

.node-icon {
    margin-right: 12px;
    font-size: 1.2em;
    opacity: 0.9;
}

.node-title {
    flex: 1;
    font-weight: 500;
}

.node-code {
    font-size: 0.85em;
    opacity: 0.8;
    margin-left: 8px;
}

.toggle-btn {
    padding: 4px 8px;
    color: inherit;
    opacity: 0.8;
    transition: all 0.3s ease;
}

.toggle-btn:hover {
    opacity: 1;
    color: inherit;
}

.toggle-btn.collapsed i {
    transform: rotate(-90deg);
}

.tree-children.collapsed {
    display: none;
}

.node-content.selected {
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.3);
    border-color: #667eea;
}

.stat-item {
    padding: 8px;
}

.stat-number {
    font-size: 2em;
    font-weight: 700;
    line-height: 1;
}

.stat-label {
    font-size: 0.85em;
    color: #6c757d;
    margin-top: 4px;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #f1f3f4;
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-label {
    font-weight: 500;
    color: #495057;
}

.detail-value {
    color: #6c757d;
}

@media (max-width: 768px) {
    .org-tree-container {
        padding: 10px;
    }
    
    .tree-children {
        margin-left: 20px;
    }
    
    .node-content {
        padding: 10px 12px;
        font-size: 0.9em;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle functionality
    document.querySelectorAll('.toggle-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const node = this.closest('.tree-node');
            const children = node.querySelector('.tree-children');
            const icon = this.querySelector('i');
            
            if (children) {
                children.classList.toggle('collapsed');
                this.classList.toggle('collapsed');
            }
        });
    });
    
    // Node selection and details
    document.querySelectorAll('.node-content').forEach(content => {
        content.addEventListener('click', function() {
            // Remove previous selection
            document.querySelectorAll('.node-content.selected').forEach(el => {
                el.classList.remove('selected');
            });
            
            // Add selection to current node
            this.classList.add('selected');
            
            // Show details
            showNodeDetails(this);
        });
    });
    
    // Expand/Collapse all
    document.getElementById('expandAll').addEventListener('click', function() {
        document.querySelectorAll('.tree-children').forEach(children => {
            children.classList.remove('collapsed');
        });
        document.querySelectorAll('.toggle-btn').forEach(btn => {
            btn.classList.remove('collapsed');
        });
    });
    
    document.getElementById('collapseAll').addEventListener('click', function() {
        document.querySelectorAll('.tree-children').forEach(children => {
            children.classList.add('collapsed');
        });
        document.querySelectorAll('.toggle-btn').forEach(btn => {
            btn.classList.add('collapsed');
        });
    });
    
    function showNodeDetails(nodeContent) {
        const node = nodeContent.closest('.tree-node');
        const type = node.dataset.type;
        const id = node.dataset.id;
        const title = nodeContent.querySelector('.node-title').textContent;
        const code = nodeContent.querySelector('.node-code').textContent;
        
        let detailsHtml = `
            <div class="mb-3">
                <h6 class="text-primary mb-3">
                    <i class="fas fa-${getIconByType(type)} me-2"></i>
                    ${getTypeLabel(type)}
                </h6>
                <div class="detail-item">
                    <span class="detail-label">Tên:</span>
                    <span class="detail-value">${title}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Mã:</span>
                    <span class="detail-value">${code}</span>
                </div>
        `;
        
        if (type === 'university') {
            const schoolCount = node.querySelectorAll('.school-node').length;
            const deptCount = node.querySelectorAll('.department-node').length;
            detailsHtml += `
                <div class="detail-item">
                    <span class="detail-label">Số Trường:</span>
                    <span class="detail-value">${schoolCount}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Số Khoa:</span>
                    <span class="detail-value">${deptCount}</span>
                </div>
            `;
        } else if (type === 'school') {
            const deptCount = node.querySelectorAll('.department-node').length;
            detailsHtml += `
                <div class="detail-item">
                    <span class="detail-label">Số Khoa:</span>
                    <span class="detail-value">${deptCount}</span>
                </div>
            `;
        } else if (type === 'department') {
            const badge = nodeContent.querySelector('.badge');
            const studentCount = badge ? badge.textContent : '0 SV';
            detailsHtml += `
                <div class="detail-item">
                    <span class="detail-label">Sinh viên:</span>
                    <span class="detail-value">${studentCount}</span>
                </div>
            `;
        }
        
        detailsHtml += '</div>';
        
        document.getElementById('nodeDetails').innerHTML = detailsHtml;
    }
    
    function getIconByType(type) {
        switch(type) {
            case 'university': return 'university';
            case 'school': return 'building';
            case 'department': return 'users';
            default: return 'circle';
        }
    }
    
    function getTypeLabel(type) {
        switch(type) {
            case 'university': return 'Trường Đại học';
            case 'school': return 'Trường';
            case 'department': return 'Khoa';
            default: return 'Không xác định';
        }
    }
    
    // Initially collapse all except first level
    document.querySelectorAll('.school-node .tree-children').forEach(children => {
        children.classList.add('collapsed');
    });
    document.querySelectorAll('.school-node .toggle-btn').forEach(btn => {
        btn.classList.add('collapsed');
    });
});
</script>
@endsection

