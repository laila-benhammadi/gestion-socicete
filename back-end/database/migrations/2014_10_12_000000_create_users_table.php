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
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email', 191)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_admin')->default(false);
            $table->date('date_of_birth');
            $table->string('gender');
            $table->string('phone_number')->nullable();
            $table->string('nationality');
            $table->string('contry');
            $table->string('city');
            $table->string('personal_email', 191)->unique(); 
            $table->string('family_situation');
            $table->string('cin');
            $table->string('address')->nullable();
            $table->string('user_folder')->nullable();
            $table->date('hiring_date')->nullable();
            $table->json('work_files')->nullable();
            $table->rememberToken();
            $table->timestamps();
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
