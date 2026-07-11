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
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body_html');
            $table->enum('category', ['maintenance', 'services', 'tender', 'policy', 'security', 'general'])
                ->default('general');
            $table->enum('status', ['draft', 'review', 'published'])->default('draft');
            $table->enum('visibility', ['public', 'internal'])->default('public');
            $table->foreignId('attachment_media_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes(); // bulk-delete recoverable, per Part 2
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
