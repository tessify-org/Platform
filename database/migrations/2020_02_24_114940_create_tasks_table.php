<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('author_id');
            $table->unsignedInteger('project_id')->nullable();
            $table->unsignedInteger('ministry_id')->nullable();
            $table->unsignedInteger('organization_id')->nullable();
            $table->unsignedInteger('organization_department_id')->nullable();
            $table->unsignedInteger('task_status_id');
            $table->unsignedInteger('task_category_id');
            $table->unsignedInteger('task_seniority_id');
            $table->string('slug');
            $table->string('title');
            $table->text('description');
            $table->unsignedInteger('complexity')->default(1);
            $table->unsignedInteger('estimated_hours');
            $table->unsignedInteger('realized_hours')->default(0);
            $table->unsignedInteger('num_positions')->default(1);
            $table->unsignedInteger('urgency')->default(1);
            $table->string('header_image_url')->default('storage/images/tasks/header/default.jpg');
            $table->boolean('has_deadline')->default(false);
            $table->date('ends_at')->nullable();
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
        Schema::dropIfExists('tasks');
    }
}
