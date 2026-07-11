<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The existing 6 rows are section summaries ('group') used on the
     * homepage teaser grid. This adds support for individual catalog cards
     * ('detail') on the full /services page — e.g. "Elastic Cloud Server
     * (ECS)" and "Cloud Storage Services" both belong to the 'cloud' group.
     */
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->enum('kind', ['group', 'detail'])->default('group')->after('slug');
            $table->string('group_slug')->nullable()->after('kind'); // for kind=detail: parent group's slug
            $table->string('tag')->nullable()->after('name'); // short subtitle, e.g. "Infrastructure as a Service"
            $table->json('tiers')->nullable(); // tier-pill labels; a trailing "*" marks it highlighted/gold
            $table->json('features')->nullable(); // feature bullet list
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['kind', 'group_slug', 'tag', 'tiers', 'features']);
        });
    }
};
