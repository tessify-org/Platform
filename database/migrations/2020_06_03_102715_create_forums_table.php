<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forums', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('parent_forum_id')->nullable();
            $table->string('forumable_type')->nullable();
            $table->string('forumable_id')->nullable();
            $table->string('slug');
            $table->string('title');
            $table->string('description')->nullable();
            $table->boolean('editable')->default(true);
            $table->boolean('deletable')->default(true);
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
        Schema::dropIfExists('forums');
    }
}
