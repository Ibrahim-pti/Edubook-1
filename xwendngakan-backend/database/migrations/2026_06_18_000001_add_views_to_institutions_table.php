<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            if (!Schema::hasColumn('institutions', 'views')) {
                $table->unsignedBigInteger('views')->default(0)->after('students_count');
            }
        });
    }

    public function down(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            if (Schema::hasColumn('institutions', 'views')) {
                $table->dropColumn('views');
            }
        });
    }
};
