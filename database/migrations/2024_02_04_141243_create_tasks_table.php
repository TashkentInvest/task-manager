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

                // $table->foreignId('assigned_user_id')->constrained('users')->onDelete('cascade'); // User assigned to the task
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Creator of the task

                $table->unsignedBigInteger('category_id');
                $table->foreign('category_id')->references('id')->on('category');
                
                $table->unsignedBigInteger('level_id')->nullable();
                $table->foreign('level_id')->references('id')->on('task_level');
                $table->text('description')->nullable();
                $table->integer('type_request')->default(0);

                $table->unsignedBigInteger('status_id')->default(1);
                $table->foreign('status_id')->references('id')->on('task_status');
                $table->softDeletes();
                // $table->timestamp('deleted_at');
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
            Schema::dropIfExists('tasks');
        }
    }
