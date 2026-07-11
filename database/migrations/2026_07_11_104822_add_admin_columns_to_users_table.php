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
        Schema::table('users', function (Blueprint $table) {
            // Admin/staff panel access. Gate: is_admin === true && status === 'active'.
            // See LARAVEL-DYNAMIZATION-PLAN.md Part 6 (Authentication Design).
            $table->boolean('is_admin')->default(false)->after('password');
            $table->enum('role', ['content_editor', 'super_admin'])->nullable()->after('is_admin');
            $table->enum('status', ['pending', 'active', 'suspended'])->default('active')->after('role');

            // Invite-based account creation (Part 6.2) — no public admin self-registration.
            $table->string('invite_token', 100)->nullable()->unique()->after('status');
            $table->timestamp('invite_expires_at')->nullable()->after('invite_token');
            $table->foreignId('invited_by')->nullable()->after('invite_expires_at')
                ->constrained('users')->nullOnDelete();

            $table->timestamp('last_login_at')->nullable()->after('invited_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('invited_by');
            $table->dropColumn(['is_admin', 'role', 'status', 'invite_token', 'invite_expires_at', 'last_login_at']);
        });
    }
};
