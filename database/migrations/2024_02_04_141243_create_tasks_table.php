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
                $table->id();
    
                // Existing columns
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Creator of the task
                $table->unsignedBigInteger('category_id')->nullable(); 
                $table->foreign('category_id')->references('id')->on('category');
                
                $table->unsignedBigInteger('status_id')->default(1);
                $table->foreign('status_id')->references('id')->on('task_status');
    
                $table->softDeletes();
                $table->timestamps();
    
                // Additional columns
                $table->string('poruchenie')->nullable();                
                $table->dateTime('issue_date')->nullable();             
                $table->dateTime('planned_completion_date')->nullable(); 
                
                $table->string('attached_file')->nullable(); 
                $table->string('attached_file_employee')->nullable(); 
                $table->text('note')->nullable(); 
                $table->string('assign_type')->nullable(); 
                $table->text('reject_comment')->nullable(); 
                $table->dateTime('reject_time')->nullable(); 
    
                // Relationship to documents
                $table->foreignId('document_id')->nullable()->constrained('documents')->nullOnDelete();
    
                // Optional role relationship
                $table->unsignedBigInteger('role_id')->nullable();
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
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
