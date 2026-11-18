<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Web registration (Fortify) only collects name, email and password, so a new user
     * has no city yet and should start as a client. The API registration still requires both.
     */
    public function up(): void
    {
        // PostgreSQL keeps an enum as a CHECK rule, and Laravel cannot change that column
        // in place; the rule stays as it is, so only the default is added there.
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE users ALTER COLUMN role SET DEFAULT 'client'");
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['client', 'freelancer', 'admin'])->default('client')->change();
            });
        }

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('city_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE users ALTER COLUMN role DROP DEFAULT');
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['client', 'freelancer', 'admin'])->change();
            });
        }

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('city_id')->nullable(false)->change();
        });
    }
};
