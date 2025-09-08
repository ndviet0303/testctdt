<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('universities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Tên đại học
            $table->string('code')->unique(); // Mã đại học (VD: PNU)
            $table->string('full_name')->nullable(); // Tên đầy đủ
            $table->text('description')->nullable(); // Mô tả
            $table->string('address')->nullable(); // Địa chỉ
            $table->string('phone')->nullable(); // Số điện thoại
            $table->string('email')->nullable(); // Email
            $table->string('website')->nullable(); // Website
            $table->string('logo')->nullable(); // Logo path
            $table->date('established_date')->nullable(); // Ngày thành lập
            $table->boolean('is_active')->default(true); // Trạng thái hoạt động
            $table->json('metadata')->nullable(); // Thông tin bổ sung
            $table->timestamps();
            
            // Indexes
            $table->index(['name', 'is_active']);
            $table->index('code');
        });
    }

    public function down()
    {
        Schema::dropIfExists('universities');
    }
};
