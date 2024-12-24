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
        
        // Generate the PDF from the view
        $pdf = Pdf::loadView('pages.monitoring.partials.fishka_pdf', compact('task'));
    
        // Return the PDF as a response
        return $pdf->download('fishka-task-' . $id . '.pdf');
    }
}
