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
        Schema::create('approval_workflows', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên quy trình (Tạo Trường mới, Thiết lập hiệu lực Trường, etc.)
            $table->string('code')->unique(); // Mã quy trình
            $table->text('description')->nullable();
            $table->enum('entity_type', ['university', 'school', 'department']); // Loại đối tượng áp dụng
            $table->enum('action_type', ['create', 'update', 'delete', 'activate', 'deactivate']); // Loại hành động
            $table->json('workflow_steps'); // Các bước trong quy trình (JSON)
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable(); // Thông tin bổ sung
            $table->timestamps();
            
            $table->index(['entity_type', 'action_type']);
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_workflows');
    }
};
