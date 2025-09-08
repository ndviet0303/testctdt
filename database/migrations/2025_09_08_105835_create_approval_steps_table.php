<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('approval_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('approval_requests')->onDelete('cascade');
            $table->integer('step_order'); // Thứ tự bước trong quy trình
            $table->string('step_name'); // Tên bước (Cấu hình hệ thống, Tạo Trường mới, etc.)
            $table->text('step_description')->nullable(); // Mô tả bước
            
            // Thông tin người phê duyệt
            $table->unsignedBigInteger('approver_id')->nullable(); // ID người phê duyệt
            $table->string('approver_name')->nullable(); // Tên người phê duyệt
            $table->string('approver_role')->nullable(); // Vai trò người phê duyệt
            $table->string('approver_department')->nullable(); // Bộ phận người phê duyệt
            
            // Trạng thái bước
            $table->enum('status', [
                'pending', 'in_progress', 'approved', 
                'rejected', 'skipped', 'cancelled'
            ])->default('pending');
            
            $table->text('comments')->nullable(); // Nhận xét của người phê duyệt
            $table->text('rejection_reason')->nullable(); // Lý do từ chối
            $table->json('step_data')->nullable(); // Dữ liệu bổ sung cho bước
            
            // Thời gian xử lý
            $table->timestamp('assigned_at')->nullable(); // Thời gian được giao
            $table->timestamp('started_at')->nullable(); // Thời gian bắt đầu xử lý
            $table->timestamp('completed_at')->nullable(); // Thời gian hoàn thành
            $table->integer('processing_time')->nullable(); // Thời gian xử lý (phút)
            
            // Cấu hình bước
            $table->boolean('is_required')->default(true); // Bước bắt buộc
            $table->boolean('can_delegate')->default(false); // Có thể ủy quyền
            $table->integer('timeout_hours')->nullable(); // Thời gian timeout (giờ)
            $table->json('escalation_rules')->nullable(); // Quy tắc leo thang
            
            $table->json('metadata')->nullable(); // Thông tin bổ sung
            $table->timestamps();
            
            $table->index(['request_id', 'step_order']);
            $table->index(['status', 'approver_id']);
            $table->index('assigned_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_steps');
    }
};
