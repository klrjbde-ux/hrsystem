<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('feedback_responses');
        Schema::dropIfExists('performance_goals');

        // Remove review_cycle_id from performance_reviews before dropping review_cycles
        if (Schema::hasTable('performance_reviews') && Schema::hasColumn('performance_reviews', 'review_cycle_id')) {
            Schema::table('performance_reviews', function (Blueprint $table) {
                $table->dropForeign(['review_cycle_id']);
                $table->dropColumn('review_cycle_id');
            });
        }

        Schema::dropIfExists('review_cycles');
    }

    public function down(): void
    {
        // Not reversible - tables were intentionally removed
    }
};
