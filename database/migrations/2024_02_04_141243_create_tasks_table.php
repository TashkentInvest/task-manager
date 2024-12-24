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
                $table->unsignedBigInteger('category_id')->nullable(); // xat, telefon qilish, etc ...
                $table->foreign('category_id')->references('id')->on('category');
                $table->unsignedBigInteger('status_id')->default(1);
                $table->foreign('status_id')->references('id')->on('task_status');
                $table->softDeletes();
                $table->timestamps();

                // New columns
                $table->string('poruchenie')->nullable(); // Поручение (Task or assignment)
                $table->date('issue_date')->nullable(); // Дата выдачи (Date of issue)
                $table->date('planned_completion_date')->nullable(); // Срок выполнения (план) (Planned completion date)
                // $table->string('author')->nullable(); // Автор поручения (Author of the task)

                $table->string('attached_file')->nullable(); // Закрепленный файл (Attached file)
                $table->string('attached_file_employee')->nullable(); // Закрепленный файл (Attached file)
                $table->text('note')->nullable(); // Примичание (Notes)

                $table->string('assign_type')->nullable(); // Вид документа (Document type)

                $table->text('reject_comment')->nullable(); // if employee will reject 
                $table->dateTime('reject_time')->nullable(); // if employee will reject 

                
                $table->foreignId('document_id')->nullable()
                    ->constrained('documents')
                    ->nullOnDelete();


                    
                // Add role_id column and foreign key
                $table->unsignedBigInteger('role_id')->nullable(); // Role associated with the task
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
