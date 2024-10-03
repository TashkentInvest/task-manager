<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserOffDayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //// old code of user_offday
        // Schema::create('user_offday', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('user_id')->references('id')->on('users');
        //     $table->text('month')->nullable();
        //     $table->json('week_days')->nullable(); //utf8mb4_unicode_ci	
        //     $table->integer('count_work_days')->nullable();
        //     $table->softDeletes();

        //     $table->timestamps();
        // });

        Schema::create('user_offday', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users');
            $table->text('month')->nullable();
            $table->string('week_days')->nullable(); // Storing weekdays as a comma-separated string
            $table->integer('count_work_days')->nullable();
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
        Schema::dropIfExists('user_off_day');
    }
}
