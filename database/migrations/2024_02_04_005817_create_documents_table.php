<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');            // Sarlavha
            $table->string('letter_number');    // Xat raqami
            $table->datetime('received_date');  // Qachon olingani
        
            // Link to user who created or owns the document
            $table->foreignId('user_id')->nullable()
                ->constrained('users')
                ->nullOnDelete();
        
            // Link to the "document_categories" table
            $table->foreignId('document_category_id')->nullable()
                ->constrained('document_categories')
                ->nullOnDelete();
        
            $table->foreignId('ministry_id')->nullable()->constrained('ministries')->nullOnDelete();
        
            // Add the status_type column (enum with two possible values)
            $table->enum('status_type', ['kiruvchi', 'chiquvchi'])->nullable();
        
            $table->timestamps();
        });
        

        // For storing multiple files for a single document:
        Schema::create('document_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')
                ->constrained('documents')
                ->cascadeOnDelete();
            $table->string('file_path');
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
        Schema::dropIfExists('document_files');
        Schema::dropIfExists('documents');
    }
}
