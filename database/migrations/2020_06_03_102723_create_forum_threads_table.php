<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForumThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forum_threads', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('forum_id');
            $table->unsignedInteger('user_id');
            $table->string('slug');
            $table->string('title');
            $table->string('description')->nullable();
            $table->text('message');
            $table->boolean('sticky')->default(false);
            $table->boolean('closed')->default(false);
            $table->unsignedInteger('order')->default(0);
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
        Schema::dropIfExists('forum_threads');
    }
}
