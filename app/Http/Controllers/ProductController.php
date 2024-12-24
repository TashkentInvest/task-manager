<?php

namespace App\Http\Controllers;

use App\Models\Tasks;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
// use Barryvdh\DomPDF\Facade as PDF;


class ProductController extends Controller
{
    public function index(){
        return view('pages.monitoring.index');
    }

    public function fishka($id)
    {
        $task = Tasks::find($id);

        \Carbon\Carbon::setLocale('uz'); // Ensure locale is set
        $monthNames = [
            'yanvar', 'fevral', 'mart', 'aprel', 'may', 'iyun',
            'iyul', 'avgust', 'sentabr', 'oktabr', 'noyabr', 'dekabr'
        ];
    
        $pdf = \PDF::loadView('pages.monitoring.partials.fishka_pdf', compact('task','monthNames'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]);
        
        return $pdf->download('fishka-task-' . $id . '.pdf');
    }
    
}
