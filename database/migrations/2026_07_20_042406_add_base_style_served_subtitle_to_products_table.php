<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('subtitle')->nullable()->after('name');
            $table->string('base')->nullable()->after('description');
            $table->string('style')->nullable()->after('base');
            $table->string('served')->nullable()->after('style');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['subtitle', 'base', 'style', 'served']);
        });
    }
};
