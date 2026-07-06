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
        Schema::create('issues', function (Blueprint $table) {
            $table->string('id')->primary(); // custom ID: ISS-xxx
            $table->enum('type', ['Bug', 'Improve', 'Request']);
            $table->enum('status', ['Unassigned', 'Assigned', 'In Progress', 'Complete'])->default('Unassigned');
            $table->enum('priority', ['Low', 'Medium', 'High'])->default('Low');
            $table->string('subject');
            $table->text('description');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('issue_attachments', function (Blueprint $table) {
            $table->id();
            $table->string('issue_id');
            $table->foreign('issue_id')->references('id')->on('issues')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('file_name');
            $table->timestamps();
        });

        Schema::create('issue_histories', function (Blueprint $table) {
            $table->id();
            $table->string('issue_id');
            $table->foreign('issue_id')->references('id')->on('issues')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('action');
            $table->text('description');
            $table->timestamps();
        });

        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('receiver_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->text('message');
            $table->boolean('is_group')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
        Schema::dropIfExists('issue_histories');
        Schema::dropIfExists('issue_attachments');
        Schema::dropIfExists('issues');
    }
};
