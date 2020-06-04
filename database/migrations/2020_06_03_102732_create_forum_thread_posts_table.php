<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForumThreadPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forum_thread_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('forum_thread_id');
            $table->unsignedInteger('forum_thread_post_id')->nullable();
            $table->unsignedInteger('user_id');
            $table->string('uuid');
            $table->text('message');
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
        Schema::dropIfExists('forum_thread_posts');
    }
}
