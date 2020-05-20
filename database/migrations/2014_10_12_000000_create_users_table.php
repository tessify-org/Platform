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
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('slug');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('avatar_url')->nullable();
            $table->string('header_bg_url')->default('storage/images/users/headers/default.jpeg');
            $table->string('phone')->nullable();
            $table->string('headline')->nullable();
            $table->text('about_me')->nullable();
            $table->unsignedInteger('reputation_points')->default(0);
            $table->boolean('is_admin')->default(false);
            $table->string('recovery_code')->nullable();
            $table->boolean('has_been_checked')->default(false);
            $table->timestamp('banned_until')->nullable();
            $table->boolean('permabanned')->default(false);
            $table->boolean('publicly_display_email')->default(false);
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
