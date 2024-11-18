<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQarorFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qaror_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('qaror_id'); // Foreign key to qarorlars table
            $table->foreign('qaror_id')->references('id')->on('qarorlars')->onDelete('cascade');

            $table->string('file_path'); // Path to the uploaded file
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
        Schema::dropIfExists('qaror_files');
    }
}
