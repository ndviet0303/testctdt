<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained()->onDelete('cascade'); // Thuộc đại học nào
            $table->string('name'); // Tên trường
            $table->string('code'); // Mã trường (VD: CNTT)
            $table->string('full_name')->nullable(); // Tên đầy đủ
            $table->text('description')->nullable(); // Mô tả
            $table->string('dean_name')->nullable(); // Tên hiệu trưởng/trưởng trường
            $table->string('phone')->nullable(); // Số điện thoại
            $table->string('email')->nullable(); // Email
            $table->string('address')->nullable(); // Địa chỉ
            $table->date('established_date')->nullable(); // Ngày thành lập
            $table->boolean('is_active')->default(true); // Trạng thái hoạt động
            $table->json('metadata')->nullable(); // Thông tin bổ sung
            $table->timestamps();
            
            // Indexes
            $table->index(['university_id', 'name']);
            $table->index(['university_id', 'code']);
            $table->index(['university_id', 'is_active']);
            $table->unique(['university_id', 'code']); // Mã trường unique trong 1 đại học
        });
    }

    public function down()
    {
        Schema::dropIfExists('schools');
    }
};
