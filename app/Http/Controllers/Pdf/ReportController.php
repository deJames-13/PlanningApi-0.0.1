<?php

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function example()
    {
        $data = [
            'title' => 'Example PDF',
            'content' => 'This is an example PDF file.',
            'date' => date('m/d/Y')
        ];
        $pdf = \PDF::loadView('pdf.example' , $data);
        return $pdf->download('pdf.example');
    }
}
