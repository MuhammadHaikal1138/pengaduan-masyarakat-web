<?php

namespace App\Http\Controllers;

use App\Exports\ReportsExport;
use App\Models\Report;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function export(Request $request){
         // Ambil provinsi dari staff yang sedang login
         $staffProvince = auth()->user()->staffProvince;
         $staffProvinceName = $staffProvince->province;
 
         // Jika ada filter berdasarkan tanggal
         if ($request->has('filter') && $request->filter === 'date' && $request->has('date')) {
             $date = $request->date;
             // Ambil laporan berdasarkan tanggal
             $reports = Report::where('province', $staffProvinceName)
                              ->whereDate('created_at', $date)
                              ->get();
             return Excel::download(new ReportsExport($reports), 'reports_' . $date . '.xlsx');
         }
         // Jika tidak ada filter, export semua laporan
         // Ambil laporan berdasarkan provinsi
         $reports = Report::where('province', $staffProvinceName)->get();
         return Excel::download(new ReportsExport($reports), 'reports_' . $staffProvinceName . '.xlsx');
        // $reports = Report::all();
        // return Excel::download(new ReportExport($reports), 'reports_all.xlsx');

    }
}
