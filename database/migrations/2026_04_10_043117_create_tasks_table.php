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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_code')->nullable()->unique();
            $table->string('title')->maxLength(50);
            $table->text('description')->nullable();
            $table->enum('severity', ['low', 'medium', 'high'])->default('low');
            $table->enum('status', ['pending', 'in progress', 'completed'])->default('pending');
            $table->date('due_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('assigned_to')->index()->constrained('users');
            $table->foreignId('created_by')->index()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};