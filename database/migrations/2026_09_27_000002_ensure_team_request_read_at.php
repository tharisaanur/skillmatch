<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('team_requests', 'read_at')) {
            Schema::table('team_requests', function (Blueprint $table) {
                $table->timestamp('read_at')->nullable()->after('status');
            });
        }
    }

    public function down(): void
    {
        // This migration repairs a column owned by an earlier migration.
    }
};
