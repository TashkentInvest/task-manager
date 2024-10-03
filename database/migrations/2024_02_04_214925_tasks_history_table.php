<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TasksHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->references('id')->on('tasks');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('category');
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('company');
            $table->unsignedBigInteger('driver_id');
            $table->foreign('driver_id')->references('id')->on('drivers');
            $table->unsignedBigInteger('level_id');
            $table->foreign('level_id')->references('id')->on('task_level');
            $table->text('description')->nullable();
            $table->integer('type_request')->default(0);
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('user_id');
            $table->softDeletes();

            $table->integer('old_status_id');
            $table->integer('new_status_id')->default(1);
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
        Schema::dropIfExists('tasks_history');
    }
}
