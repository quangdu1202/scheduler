<?php

use App\Models\Module;
use App\Models\PracticeRoom;
use App\Models\Teacher;
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
            $table->enum('session', [1, 2, 3]);
            $table->foreignIdFor(Module::class);
            $table->foreignIdFor(PracticeRoom::class);
            $table->foreignIdFor(Teacher::class)->nullable();
            $table->integer('recurring_id')->nullable();
            $table->integer('registered_qty')->default(0);
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
