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
        Schema::create('approval_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')->constrained('approval_workflows')->onDelete('cascade');
            $table->string('request_code')->unique(); // Mã yêu cầu duy nhất
            $table->string('title'); // Tiêu đề yêu cầu
            $table->text('description')->nullable(); // Mô tả yêu cầu
            
            // Thông tin người yêu cầu
            $table->unsignedBigInteger('requester_id'); // ID người yêu cầu
            $table->string('requester_name'); // Tên người yêu cầu
            $table->string('requester_email'); // Email người yêu cầu
            $table->string('requester_department')->nullable(); // Bộ phận của người yêu cầu
            
            // Thông tin đối tượng được yêu cầu
            $table->enum('entity_type', ['university', 'school', 'department']);
            $table->unsignedBigInteger('entity_id')->nullable(); // ID đối tượng (nếu update/delete)
            $table->json('entity_data'); // Dữ liệu đối tượng (create/update)
            $table->json('original_data')->nullable(); // Dữ liệu gốc (cho update)
            
            // Trạng thái yêu cầu
            $table->enum('status', [
                'draft', 'submitted', 'in_review', 'approved', 
                'rejected', 'cancelled', 'completed'
            ])->default('draft');
            
            $table->integer('current_step')->default(0); // Bước hiện tại trong quy trình
            $table->text('rejection_reason')->nullable(); // Lý do từ chối
            $table->json('approval_history')->nullable(); // Lịch sử phê duyệt
            
            // Thời gian
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            $table->json('metadata')->nullable(); // Thông tin bổ sung
            $table->timestamps();
            
            $table->index(['status', 'entity_type']);
            $table->index(['requester_id', 'status']);
            $table->index('request_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_requests');
    }
};
