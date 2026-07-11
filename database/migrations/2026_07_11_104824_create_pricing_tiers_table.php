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
        Schema::create('pricing_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('tier_key')->unique(); // matches data-tier-id in the static HTML, e.g. ecs-x-small
            $table->string('service_type'); // e.g. cloud_ecs, rbs_vps — groups tiers per page section
            $table->string('name');
            $table->decimal('price_value', 12, 2)->nullable(); // numeric, for sorting/calculation
            $table->string('price_display'); // pre-formatted BDT string shown as-is
            $table->json('specs')->nullable(); // vCPU, RAM, storage, etc.
            $table->boolean('is_visible')->default(true);
            $table->unsignedSmallInteger('display_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_tiers');
    }
};
