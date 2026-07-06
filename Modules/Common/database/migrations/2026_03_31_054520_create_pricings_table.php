<?php
// Modules/Common/Database/Migrations/2026_03_31_000000_create_pricings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pricings', function (Blueprint $table) {
            $table->id();

            // Basic Information
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('short_description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('currency', 3)->default('Rs.');
            $table->string('period')->default('/ year'); // / year, / month, / one-time

            // Features (JSON)
            $table->json('features')->nullable();

            // Settings
            $table->boolean('is_popular')->default(false);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            // Button Text
            $table->string('button_text')->default('Get Started');
            $table->string('button_url')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['is_active', 'sort_order']);
            $table->index('slug');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pricings');
    }
};
