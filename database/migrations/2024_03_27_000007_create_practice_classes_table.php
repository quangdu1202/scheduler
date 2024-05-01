<?php

use App\Models\Module\Module;
use App\Models\PracticeRoom\PracticeRoom;
use App\Models\Teacher\Teacher;
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
        Schema::create('practice_classes', function (Blueprint $table) {
            $table->id();
            $table->string('practice_class_name');
            $table->date('schedule_date')->nullable();
            $table->integer('session')->nullable();
            $table->foreignId('module_id')->constrained('modules');
            $table->foreignId('practice_room_id')->nullable()->constrained('practice_rooms');
            $table->foreignId('teacher_id')->nullable()->constrained('teachers');
            $table->string('recurring_id');
            $table->integer('recurring_interval')->default(0);
            $table->integer('recurring_order')->default(1);
            $table->integer('registered_qty')->default(0);
            $table->integer('max_qty');
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practice_classes');
    }
};
