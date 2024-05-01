<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_marks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_class_id')->constrained('module_classes');
            $table->foreignId('practice_class_id')->constrained('practice_classes');
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('mark_type_id')->constrained('mark_types');
            $table->decimal('mark_value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_marks');
    }
};
