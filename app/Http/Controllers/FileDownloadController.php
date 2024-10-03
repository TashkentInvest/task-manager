<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SampleExport;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class FileDownloadController extends Controller
{
    public function downloadPdf()
    {
        $pdf = \PDF::loadView('pdf.index');
        return $pdf->download('sample.pdf');
    }


   
public function downloadExcel()
{
    $users = User::all();

    $headers = [
        'Content-Type' => 'application/vnd.ms-excel',
        'Content-Disposition' => 'attachment; filename="users.xlsx"',
    ];

    Excel::download(function($excel) use ($users) {
        $excel->sheet('Users', function($sheet) use ($users) {
            $sheet->fromArray($users);
        });
    }, 'users.xlsx');

    return Response::make('', 200, $headers);
}
    public function downloadCsv()
    {
        $data = User::all(); 
    
        $csvContent = "id,name,email\n";
    
        foreach ($data as $row) {
            $csvContent .= "{$row->id},{$row->name},{$row->email}\n";
        }
    
        return response()->streamDownload(function () use ($csvContent) {
            echo $csvContent;
        }, 'sample.csv');
    }

}
