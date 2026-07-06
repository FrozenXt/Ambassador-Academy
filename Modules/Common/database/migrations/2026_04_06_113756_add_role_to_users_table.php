<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('staff')->after('is_admin');
            $table->string('phone')->nullable()->after('role');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('phone');
            $table->string('avatar')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'status', 'avatar']);
        });
    }
};
