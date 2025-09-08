<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade'); // Thuộc trường nào
            $table->string('name'); // Tên khoa
            $table->string('code'); // Mã khoa (VD: HTTT)
            $table->string('full_name')->nullable(); // Tên đầy đủ
            $table->text('description')->nullable(); // Mô tả
            $table->string('head_name')->nullable(); // Tên trưởng khoa
            $table->string('phone')->nullable(); // Số điện thoại
            $table->string('email')->nullable(); // Email
            $table->string('office_location')->nullable(); // Vị trí văn phòng
            $table->date('established_date')->nullable(); // Ngày thành lập
            $table->integer('student_count')->default(0); // Số lượng sinh viên
            $table->integer('staff_count')->default(0); // Số lượng cán bộ
            $table->boolean('is_active')->default(true); // Trạng thái hoạt động
            $table->json('metadata')->nullable(); // Thông tin bổ sung (chương trình đào tạo, etc.)
            $table->timestamps();
            
            // Indexes
            $table->index(['school_id', 'name']);
            $table->index(['school_id', 'code']);
            $table->index(['school_id', 'is_active']);
            $table->unique(['school_id', 'code']); // Mã khoa unique trong 1 trường
        });
    }

    public function down()
    {
        Schema::dropIfExists('departments');
    }
};
