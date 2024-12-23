<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); //employee_id
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('finished_user_id')->nullable(); // finished employee id
            $table->foreign('finished_user_id')->references('id')->on('users');

            $table->unsignedBigInteger('task_id');
            $table->foreign('task_id')->references('id')->on('tasks');

            $table->integer('completed_time')->nullable();
            
            $table->integer('checked_status')->default(0); // CEO or admin check & approve
            $table->text('checked_comment')->nullable(); 
            $table->dateTime('checked_time')->nullable(); 

            $table->softDeletes();
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
        Schema::dropIfExists('orders');
    }
}
