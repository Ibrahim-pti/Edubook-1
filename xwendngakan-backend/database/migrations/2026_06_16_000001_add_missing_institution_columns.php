<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            if (!Schema::hasColumn('institutions', 'is_premium')) {
                $table->boolean('is_premium')->default(false)->after('approved');
            }
            if (!Schema::hasColumn('institutions', 'manager_name')) {
                $table->string('manager_name')->nullable()->after('phone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            $table->dropColumn(['is_premium', 'manager_name']);
        });
    }
};
