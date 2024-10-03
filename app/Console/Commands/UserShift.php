<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\UserOffDay;

class UserShift extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:shift';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create new shift for users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $test = UserOffDay::createShiftByMonth();
        Log::info('shift: ',$test);
    }
}
