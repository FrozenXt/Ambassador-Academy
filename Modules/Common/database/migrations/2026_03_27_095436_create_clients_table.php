<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();

            // Basic Information
            $table->string('client_code')->unique();
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('website')->nullable();
            $table->string('tax_number')->nullable(); // VAT KI GST HALNI
            $table->string('registration_number')->nullable(); // Company ko registration number

            // Address Information
            $table->text('address_line1')->nullable();
            $table->text('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();

            // Contact Person Details
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_email')->nullable();
            $table->string('contact_person_phone')->nullable();
            $table->string('contact_person_designation')->nullable();

            // Business Details
            $table->string('industry_type')->nullable();
            $table->integer('employee_count')->nullable();
            $table->decimal('annual_revenue', 15, 2)->nullable();
            $table->string('currency', 3)->default('USD');

            // Account Management
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('client_type', ['individual', 'business', 'government', 'nonprofit'])->default('business');
            $table->enum('payment_terms', ['immediate', 'net_7', 'net_15', 'net_30', 'net_60'])->default('net_30');
            $table->decimal('credit_limit', 15, 2)->nullable();

            // Status and Settings
            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->text('notes')->nullable();

            // Preferences
            $table->json('preferences')->nullable(); // For storing client preferences
            $table->json('social_media')->nullable(); // Social media links

            // Timestamps
            $table->timestamps();
            $table->softDeletes(); // For soft delete

            // Indexes for better performance
            $table->index(['client_code', 'is_active']);
            $table->index(['name', 'company_name']);
            $table->index('industry_type');
            $table->index('assigned_to');
        });
    }

    public function down()
    {
        Schema::dropIfExists('clients');
    }
};
