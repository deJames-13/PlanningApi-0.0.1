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
        return $pdf->download('example.pdf');
    }

    public function sectors(string $id)
    {
        $sector = \App\Models\Sector::find($id);
        if(!$sector) {
            return response()->json(['message' => 'Sector not found'], 404);
        }

        \Log::info('Sector PDF generated', ['sector' => $sector]);
        $data = [
            'title' => 'Sector PDF',
            'content' => 'This is a sector PDF file.',
            'date' => date('m/d/Y'),
            'sector' => $sector
        ];
        $pdf = \PDF::loadView('pdf.example' , $data);
        return $pdf->download("sector_$id.pdf");
    }

    public function bar(string $id)
    {
        $bar = \App\Models\BarData::find($id);
        if(!$sector) {
            return response()->json(['message' => 'BAR not found'], 404);
        }

        $data = [
            'title' => 'BAR PDF',
            'content' => 'This is a BAR PDF file.',
            'date' => date('m/d/Y'),
            'bar' => $bar
        ];
        $pdf = \PDF::loadView('pdf.example' , $data);
        return $pdf->download("bar_$id.pdf");
    }
}
