<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('author_id');
            $table->unsignedInteger('project_status_id');
            $table->unsignedInteger('project_category_id');
            $table->unsignedInteger('project_phase_id')->nullable();
            $table->unsignedInteger('ministry_id')->nullable();
            $table->unsignedInteger('organization_id')->nullable();
            $table->unsignedInteger('organization_department_id')->nullable();
            $table->unsignedInteger('work_method_id')->nullable();
            $table->string('slug');
            $table->string('title');
            $table->json('slogan')->nullable();
            $table->json('description')->nullable();
            $table->string('header_image_url')->default('storage/images/projects/header/default.png');
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->boolean('has_tasks')->default(false);
            $table->boolean('has_deadline')->default(false);
            $table->unsignedInteger('budget')->nullable();
            $table->string('project_code')->nullable();
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
        Schema::dropIfExists('projects');
    }
}
