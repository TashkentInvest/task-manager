<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserOffdaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_offdays', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('user_off_day_id')->references('id')->on('user_offday');
            $table->foreignId('user_off_day_id')->references('id')->on('user_offday')->onDelete('cascade');

            $table->foreignId('user_id')->references('id')->on('users');
            $table->date('date');
            $table->date('additional_date')->nullable();
            $table->integer('type')->default(1);
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('user_offdays');
    }
}
