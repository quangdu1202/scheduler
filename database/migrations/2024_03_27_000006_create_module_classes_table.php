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
        Schema::create('module_classes', function (Blueprint $table) {
            $table->id();
            $table->string('module_class_code');
            $table->string('module_class_name');
            $table->foreignId('module_id')->constrained('modules');
            $table->foreignId('teacher_id')->constrained('teachers');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('student_qty');
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_classes');
    }
};
