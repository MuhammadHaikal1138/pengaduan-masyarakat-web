<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function report(Request $request){
        // Ambil provinsi staff yang sedang login
        $staffProvince = auth()->user()->staffProvince;
        
        // Ambil provinsi dari staff
        $staffProvinceName = $staffProvince->province;

        // Ambil kolom sort dan urutan order dari request, jika tidak ada gunakan default
        $sortBy = $request->input('sort', 'voting'); // Default kolom 'votes'
        $order = $request->input('order', 'asc');  // Default urutan 'asc'

        // Ambil laporan yang hanya ada di provinsi yang terkait dengan staff yang login
        $reports = Report::where('province', $staffProvinceName) ->orderBy($sortBy, $order)->get();

        return view('staff.report', compact('reports', 'sortBy', 'order','staffProvinceName'));
    }
}
