<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            // user_id
            if (!Schema::hasColumn('institutions', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }

            // lat / lng
            if (!Schema::hasColumn('institutions', 'lat')) {
                $table->decimal('lat', 10, 7)->nullable()->after('addr');
            }
            if (!Schema::hasColumn('institutions', 'lng')) {
                $table->decimal('lng', 10, 7)->nullable()->after('lat');
            }

            // video
            if (!Schema::hasColumn('institutions', 'video')) {
                $table->text('video')->nullable()->after('img');
            }

            // tuition_plans
            if (!Schema::hasColumn('institutions', 'tuition_plans')) {
                $table->json('tuition_plans')->nullable()->after('depts');
            }

            // multilingual descriptions
            if (!Schema::hasColumn('institutions', 'desc_en')) {
                $table->text('desc_en')->nullable()->after('desc');
            }
            if (!Schema::hasColumn('institutions', 'desc_ar')) {
                $table->text('desc_ar')->nullable()->after('desc_en');
            }

            // stats
            if (!Schema::hasColumn('institutions', 'founded_year')) {
                $table->unsignedSmallInteger('founded_year')->nullable()->after('video');
            }
            if (!Schema::hasColumn('institutions', 'students_count')) {
                $table->unsignedInteger('students_count')->nullable()->after('founded_year');
            }
        });
    }

    public function down(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            $table->dropColumn([
                'user_id', 'lat', 'lng', 'video',
                'tuition_plans', 'desc_en', 'desc_ar',
                'founded_year', 'students_count',
            ]);
        });
    }
};
