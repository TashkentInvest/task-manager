<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

class DemoController extends Controller
{
    public function toggleCommand(Request $request)
    {
        // dd($request);
        $action = $request->input('action');

        if ($action === 'start') {
            Artisan::call('demo:cron');
            Artisan::call('demo:cron:start');
            $response = 'Command started successfully.';

            $onlineUsers = User::where('is_online', 1)
            ->whereNotIn('id', [1, 2, 3])
            ->with(['orders' => function($query){
                $query->where('status', 0);
            }])
            ->with('tasks')
            ->get();
            
            foreach($onlineUsers as $user){
                $user->last_finishedtask = now(); 
                $user->save(); 
            }


        } elseif ($action === 'stop') {
            Artisan::call('demo:cron:stop');
            $response = 'Command stopped successfully.';
        } else {
            return response('Invalid action.', 400);
        }

        return redirect()->back()->with('status', $response);
    }

    public function getStatus()
    {
      //$statusFilePath = '/Users/user/Desktop/logistic_adminLte/public/status.txt';
    $statusFilePath = public_path('status.txt');
		
        $statusContent = file_get_contents($statusFilePath) ;
        // var_dump($statusContent);
        $statusData = json_decode($statusContent, true);
        $my_demo_status = $statusData['stop_demo_cron'];
        return response()->json(['stop_demo_cron' => $my_demo_status]);
    }
    // public function executeCommand()
    // {
    //     Artisan::call('demo:cron');
    //     return 'Command executed successfully!';
    // }

    

    // public function executeCommand($command)
    // {
    //     if ($command === 'on') {
    //         // Logic for turning on
    //         // For example: Artisan::call('demo:cron:start');
    //     } elseif ($command === 'off') {
    //         // Logic for turning off
    //         // For example: Artisan::call('demo:cron:stop');
    //     }

    //     return 'Command executed successfully!';
    // }
        
}


