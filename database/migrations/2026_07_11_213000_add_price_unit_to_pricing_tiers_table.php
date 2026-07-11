<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pricing_tiers', function (Blueprint $table) {
            // When set, price_display is auto-generated from price_value + price_unit
            // on save (e.g. "৳3,000.00/mo") instead of being typed by hand — see
            // PricingController::formatDisplay(). Null means price_display is a
            // hand-entered custom string (used for non-numeric prices like
            // "Contact for Quote" or compound one-time+recurring fees).
            $table->string('price_unit', 30)->nullable()->after('price_value');
        });
    }

    public function down(): void
    {
        Schema::table('pricing_tiers', function (Blueprint $table) {
            $table->dropColumn('price_unit');
        });
    }
};
