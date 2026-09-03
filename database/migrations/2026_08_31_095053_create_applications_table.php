<?php
// database/migrations (or Modules/Common/Database/Migrations/xxxx_xx_xx_xxxxxx_create_applications_table.php)

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();

            $table->string('applying_for');            // Grade being applied for
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('dob');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('guardian_name');
            $table->string('email');
            $table->string('phone');
            $table->text('address');

            $table->enum('status', ['unread', 'read', 'replied'])->default('unread');
            $table->text('admin_reply')->nullable();
            $table->timestamp('replied_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
