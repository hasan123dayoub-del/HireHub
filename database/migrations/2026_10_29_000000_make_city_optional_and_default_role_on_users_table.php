<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Web registration (Fortify) only collects name, email and password, so a new user
     * has no city yet and should start as a client. The API registration still requires both.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['client', 'freelancer', 'admin'])->default('client')->change();
            $table->foreignId('city_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['client', 'freelancer', 'admin'])->change();
            $table->foreignId('city_id')->nullable(false)->change();
        });
    }
};
