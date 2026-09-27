<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'profile_photo')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('profile_photo')->nullable();
            });
        }

        if (! Schema::hasColumn('users', 'study_program')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('study_program')->nullable();
            });
        }

        if (! Schema::hasColumn('users', 'semester')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedTinyInteger('semester')->nullable();
            });
        }

        if (! Schema::hasColumn('users', 'university')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('university')->nullable();
            });
        }

        if (! Schema::hasColumn('users', 'team_status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('team_status')
                    ->default('active');
            });
        }

        if (! Schema::hasColumn('users', 'skills')) {
            Schema::table('users', function (Blueprint $table) {
                $table->json('skills')->nullable();
            });
        }
    }


    public function down(): void
    {
        if (Schema::hasColumn('users', 'profile_photo')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('profile_photo');
            });
        }

        if (Schema::hasColumn('users', 'study_program')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('study_program');
            });
        }

        if (Schema::hasColumn('users', 'semester')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('semester');
            });
        }

        if (Schema::hasColumn('users', 'university')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('university');
            });
        }

        if (Schema::hasColumn('users', 'team_status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('team_status');
            });
        }

        if (Schema::hasColumn('users', 'skills')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('skills');
            });
        }
    }
};