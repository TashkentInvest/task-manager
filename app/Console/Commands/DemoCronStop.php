<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class DemoCronStop extends Command
{
    protected $signature = 'demo:cron:stop';
    protected $description = 'Stop the demo:cron command';

    public function handle(){
        //$statusFilePath = '/Users/user/Desktop/logistic_adminLte/public/status.txt';
        $statusFilePath = public_path('status.txt');
        
        $statusContent = file_get_contents($statusFilePath) ;
        // var_dump($statusContent);
        $statusData = json_decode($statusContent, true);
        $my_demo_status = $statusData['stop_demo_cron'];
        Log::info('lalala_stop: ' . $my_demo_status . ' mmmmmm');

        
        
        $status = 1;
        File::put('status.txt', json_encode(['stop_demo_cron' => $status]));  

        // Config::set('democron', $config);

        $this->info('demo:cron command will stop on the next run.');

        Log::info('DemoCronStop was stopped successfully.');

        return 0;
    }
}
