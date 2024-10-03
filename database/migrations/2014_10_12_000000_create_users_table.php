<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('theme',30)->default('default');
            $table->integer('is_online')->default(0);
            $table->string('phone')->nullable();
            $table->text('about')->nullable();
            $table->text('location')->nullable();
            $table->date('birth_date')->nullable();
            $table->date('hire_date')->nullable();
            $table->string('avatar')->nullable();
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamp('last_finishedtask')->nullable();
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
        Schema::dropIfExists('users');
    }
}
