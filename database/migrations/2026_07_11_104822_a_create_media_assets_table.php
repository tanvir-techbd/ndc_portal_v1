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
        Schema::create('media_assets', function (Blueprint $table) {
            $table->id();
            $table->string('original_filename');
            $table->string('storage_path'); // relative to Storage::disk(), e.g. media/2026/07/{uuid}.pdf
            $table->string('public_url', 2048)->nullable(); // cached resolved URL
            $table->string('mime_type', 150);
            $table->unsignedBigInteger('size_bytes');
            $table->enum('category', ['notice', 'team_photo', 'logo', 'general'])->default('general');
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_assets');
    }
};
