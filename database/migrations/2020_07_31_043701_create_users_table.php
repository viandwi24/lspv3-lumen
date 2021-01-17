<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40);
            $table->string('username', 15)->unique();
            $table->string('email', 40)->unique();
            $table->string('phone', 16)->unique()->nullable();
            $table->string('password', 128);
            $table->longText('signature')->nullable();
            $table->string('identity_number', 225)->nullable();
            $table->enum('identity_number_type', ['NIK', 'NIS', 'SIM', 'Custom'])->default('Custom');
            $table->enum('status', ['Active','Inactive','Suspended'])->default('Active');
            $table->enum('role', ['Admin','Superadmin','Assessor', 'Accession'])->default('Accession');
            $table->dateTime('last_login')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
