<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid');
            $table->unsignedInteger('user_id');                 // The user that needs to write a review
            $table->string('reviewrequestable_type');           // Target entity TYPE that triggered this review request (task, project)
            $table->unsignedInteger('reviewrequestable_id');    // Target entity ID ...  
            $table->string('reason');                           // Action that triggered this review: 'completed_task' or 'completed_project'
            $table->string('status')->default('open');          // 'open', 'rejected' or 'fulfilled'
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
        Schema::dropIfExists('review_requests');
    }
}
