<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMediaFieldsToGalleryTable extends Migration
{
    public function up()
    {
        Schema::table('gallery', function (Blueprint $table) {
            $table->enum('file_type', ['image', 'video', 'youtube'])->after('id');
            $table->string('path')->nullable()->after('file_type');
            $table->string('youtube_url')->nullable()->after('path');
        });
    }

    public function down()
    {
        Schema::table('gallery', function (Blueprint $table) {
            $table->dropColumn(['file_type', 'path', 'youtube_url']);
        });
    }
}
