<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appraisals', function (Blueprint $table) {
            $table->dropColumn('rating_scale');
        });
    }

    public function down(): void
    {
        Schema::table('appraisals', function (Blueprint $table) {
            $table->string('rating_scale')->nullable()->after('rating');
        });
    }
};
