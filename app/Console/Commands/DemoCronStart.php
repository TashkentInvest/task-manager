<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;



class DemoCronStart extends Command
{
    protected $signature = 'demo:cron:start';
    protected $description = 'Start the demo:cron command';

    public function handle()
    {
        // $config = config('democron');

        // $config['stop_demo_cron'] = false;
      //$statusFilePath = '/Users/user/Desktop/logistic_adminLte/public/status.txt';
        $statusFilePath = public_path('status.txt');
		
        $statusContent = file_get_contents($statusFilePath) ;
        // var_dump($statusContent);
        $statusData = json_decode($statusContent, true);
        $my_demo_status = $statusData['stop_demo_cron'];
        Log::info('lalala_start: ' . $my_demo_status . ' mmmmmm');

        $status = 0;
        File::put('status.txt', json_encode(['stop_demo_cron' => $status]));  

        // User::query()->update(['updated_at' => now()->addSeconds($user->id)]);
        // User::query()->where('status', 'online')->update(['updated_at' => now()->addSeconds($user->id)]);

        // *********************************************
                
        $connection = DB::connection()->getDriverName();

        $query = DB::table('users')->where('is_online', 1);

        if ($connection === 'sqlite') {
            $query->update([
                'updated_at' => DB::raw('datetime(\'now\', \'+\' || id || \' seconds\')')
            ]);
        } elseif ($connection === 'mysql') {
            $query->update([
                'updated_at' => DB::raw('DATE_ADD(NOW(), INTERVAL id SECOND)')
            ]);
        } else {
            Log::info('baza_error ');

        }


        // *********************************************
        // $query = DB::table('users')->where('status', 'online');

        // if (Schema::hasColumn('users', 'id')) {
        //     $firstUserId = DB::table('users')->where('status', 'online')->value('id');
        
        //     if ($firstUserId !== null) {
        //         $timestamp = now()->addSeconds($firstUserId);
        //         $query->update(['updated_at' => $timestamp]);
        //     } else {
        //         Log::info('No user found with the specified conditions');
        //     }
        // } else {
        //     Log::info('The users table does not have an "id" column');
        // }

        // *********************************************

      
        // $users = User::all();

        // foreach ($users as $user) {
        //     $timestamp = now()->addSeconds($user->id);
        //     $user->update(['updated_at' => $timestamp]);
        // }

        $this->info('demo:cron command will start on the next run.');

        Log::info('DemoCronStar was started successfully.');

        return 0;
    }
}
