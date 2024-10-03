<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\Tasks;
use App\Models\TaskStatus;
use Carbon\Carbon;

class DemoCron extends Command
{
    protected $signature = 'demo:cron';
    protected $description = 'Perform tasks for online users and product ordering';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $statusFilePath = public_path('status.txt');

        try {
            $statusContent = file_get_contents($statusFilePath);
            $statusData = json_decode($statusContent, true);

            $stopDemoCron = $statusData['stop_demo_cron'];
            Log::info('Cron status: ' . $stopDemoCron);

            if ($stopDemoCron == 1) {
                Log::info('DemoCron has been stopped. --------------------------------');
                return;
            }
            Log::info('AFTER ORIGINAL WORK Cron status: ' . $stopDemoCron);

            $oldestTask = Tasks::whereDoesntHave('orders')
                ->whereNotIn('type_request', [1, 2])
                ->orderBy('created_at')
                ->with('category')
                ->first();

            Log::info('oldestTask dd: ' . $oldestTask);

            if ($oldestTask && $oldestTask->type_request == 0) {
                Log::info('oldestTask in if: ' . $oldestTask);
                
                // online userlani olamz
                $onlineUsers = User::where('is_online', 1)
                    ->whereNotIn('id', [1, 2, 3])
                    ->with(['orders' => function($query){
                        // $query->where('status', [0,5]);
                        $query->whereIn('status', [0, 5])
                        ->where('status','!=', 1)
                        // $user->orders->isEmpty() 
                        ;
                    }])
                    ->with('tasks')
                    ->get();

                // $onlineUsers = User::where('is_online', 1)
                //     ->whereNotIn('id', [1, 2, 3])
                //     ->whereDoesntHave('orders', function($query) {
                //         $query->where('status', 1);
                //     })
                //     ->with(['orders' => function($query) {
                //         $query->whereIn('status', [0, 5]);
                //     }])
                //     ->with('tasks')
                //     ->get();


                Log::info('onlineUsers in--------------------------: ' . $onlineUsers);


                Log::info('onlineUsers in: ' . $onlineUsers);
                
                if(!empty($onlineUsers)){
                    
                    foreach ($onlineUsers as $user) {
                        if ($user->orders->isEmpty()) {

                        
                        Log::info('user for in: ' . $user);
                
                        $lastFinishedTaskTime = $user->last_finishedtask ?? $user->updated_at;
                        $lastFinishedTaskTime =  Carbon::parse($lastFinishedTaskTime);
                        Log::info('$lastFinishedTaskTime Carbon in: ' . $lastFinishedTaskTime);
                        // if ($lastFinishedTaskTime) {
                            if ($lastFinishedTaskTime instanceof Carbon && $lastFinishedTaskTime->copy()->addMinutes(5)->lte(Carbon::now())) {

                            // if ($lastFinishedTaskTime->addMinutes(1) <= now()) {
                                Log::info('$lastFinishedTaskTime 99999999 in: ' . $lastFinishedTaskTime);

                            if ($user->orders->isEmpty() || ($user->orders[0]->status != 0 ) || ($user->orders[0]->status != 5 )){
                                // if (empty($user->orders) || $user->orders[0]->status != 0 ){
                                
                                if (!$user->orders->isEmpty())
                                    Log::info('Status in: ' . $user->orders[0]->status );
                                Log::info('$lastFinishedTaskTime in: ' . $lastFinishedTaskTime);
                
                                // yengi order
                                $newOrder = new Order();
                                $newOrder->user_id = $user->id;
                                $newOrder->task_id = $oldestTask->id;
                                $newOrder->status = TaskStatus::ORDER_ACTIVE;
                                
                                // deadline
                                $currentTime = now();
                                $deadline = $currentTime->addMinutes($oldestTask->category->deadline);
                                $newOrder->deadline = $deadline;
                                $newOrder->save();
            
                                // task status update yani tugadi dgan manoda
                                $oldestTask->status_id = 3; 
                                $oldestTask->save();
                                
                                Log::info("New order created for User {$user->id}.");
                                break; 
                            }
                        }
                    }
                    else{
                        Log::info('lalalallamamama');
                    }
                    }
                }else{
                    Log::info("online userlar yo'q.");
                }
            }
            Log::info("Product ordering job completed.");
        } catch (\Exception $e) {
            Log::error("Product ordering job failed: " . $e->getMessage());
        }
        return 0;
    }
}