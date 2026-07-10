<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('issues')) {
            Schema::table('issues', function (Blueprint $table) {
                if (!Schema::hasColumn('issues', 'title')) {
                    $table->string('title')->after('subject')->nullable();
                }
                if (!Schema::hasColumn('issues', 'deadline')) {
                    $table->date('deadline')->nullable()->after('priority');
                }
            });
        }

        Schema::create('group_chats', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('avatar')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->boolean('is_main')->default(false);
            $table->timestamps();
        });

        Schema::create('group_chat_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_chat_id')->constrained('group_chats')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unique(['group_chat_id', 'user_id']);
            $table->timestamps();
        });

        Schema::table('chats', function (Blueprint $table) {
            if (!Schema::hasColumn('chats', 'group_chat_id')) {
                $table->foreignId('group_chat_id')->nullable()->after('receiver_id')->constrained('group_chats')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('chats', function (Blueprint $table) {
            if (Schema::hasColumn('chats', 'group_chat_id')) {
                $table->dropForeign(['group_chat_id']);
                $table->dropColumn('group_chat_id');
            }
        });

        Schema::dropIfExists('group_chat_members');
        Schema::dropIfExists('group_chats');

        if (Schema::hasTable('issues')) {
            Schema::table('issues', function (Blueprint $table) {
                if (Schema::hasColumn('issues', 'deadline')) {
                    $table->dropColumn('deadline');
                }
                if (Schema::hasColumn('issues', 'title')) {
                    $table->dropColumn('title');
                }
                if (Schema::hasColumn('issues', 'subject')) {
                    $table->string('subject')->change();
                }
            });
        }
    }
};
