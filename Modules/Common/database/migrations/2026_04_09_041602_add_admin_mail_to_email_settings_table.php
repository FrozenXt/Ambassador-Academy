<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('email_settings', function (Blueprint $table) {
            $table->string('admin_mail')
                ->nullable()
                ->after('from_name')
                ->comment('Admin email that receives contact form submissions');
        });
    }

    public function down()
    {
        Schema::table('email_settings', function (Blueprint $table) {
            $table->dropColumn('admin_mail');
        });
    }
};
