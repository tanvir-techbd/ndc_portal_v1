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
        Schema::create('contact_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('organization')->nullable(); // free-text; no Organization model
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('inquiry_type')->nullable(); // matches #cfSubject option values; null for login-page submissions
            $table->text('message');
            $table->enum('status', ['new', 'in_progress', 'resolved'])->default('new');
            $table->string('source')->default('contact_page'); // contact_page | account_access_page
            $table->ipAddress('submitted_from_ip')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_inquiries');
    }
};
