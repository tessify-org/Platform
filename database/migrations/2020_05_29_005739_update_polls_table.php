<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("polls", function(Blueprint $table) {
            $table->unsignedInteger("num_votes")->default(0);
            $table->string("header_image_url")->default("storage/images/polls/default.jpg");
            $table->unsignedInteger("pollable_id")->nullable();
            $table->string("pollable_type")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("polls", function(Blueprint $table) {
            $table->dropColumn("num_votes");
            $table->dropColumn("header_image_url");
            $table->dropColumn("pollable_type");
            $table->dropColumn("pollable_id");
        });
    }
}
