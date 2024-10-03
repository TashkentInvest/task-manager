<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

 
    public function report(Throwable $e)
    {
        $data = [
            'description'=>$e->getMessage(),
            'file'=>$e->getFile(),
            'line'=>$e->getLine(),
            'code'=>$e->getCode(),
        ];

        \Illuminate\Support\Facades\Http::post('https://api.telegram.org/bot7060513269:AAGWi8QyHRZjgm0QY8l5j5KxmROyS8ixuCU/sendMessage',[
            'chat_id' => -4104720157,
            'text' => (string)view('pages.report.report',$data),
            'parse_mode'=>'html'
        ]);
        

    }

    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
