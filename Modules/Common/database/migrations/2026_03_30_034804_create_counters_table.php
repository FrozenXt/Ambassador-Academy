<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('counters', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('number');        // e.g. "500", "10K", "99.9"
            $table->string('suffix')->nullable(); // e.g. "+", "%", "K"
            $table->string('prefix')->nullable(); // e.g. "$", "~"
            $table->string('icon')->nullable();   // e.g. "fas fa-users"
            $table->string('description')->nullable();
            $table->string('color')->default('#4f46e5'); // icon/accent color
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('counters');
    }
};
