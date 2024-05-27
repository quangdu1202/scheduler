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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('practice_class_id')->constrained('practice_classes')->cascadeOnDelete();
            $table->date('schedule_date')->nullable();
            $table->foreignId('practice_room_id')->nullable()->constrained('practice_rooms');
            $table->integer('session')->nullable();
            $table->string('session_id');
            $table->integer('shift')->nullable();
            $table->integer('order');
            $table->integer('student_qty')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practice_class_schedules');
    }
};
