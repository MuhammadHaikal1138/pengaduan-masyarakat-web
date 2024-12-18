<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Response;
use Illuminate\Http\Request;

class ResponseController extends Controller
{
    public function response(Request $request, $id){
        $request->validate([
            'response_status' => 'required|in:ON_PROCESS,REJECT',
        ]);

        $response =Response::updateOrCreate([
            'report_id' => $id
        ],
        [
            'response_status' => $request->response_status,
            'report_id' => $id,
            'staff_id' => auth()->id(),
        ]);
        
        if ($response->response_status == 'ON_PROCESS') {
            return redirect()->route('response.index', $id);
        } else {
            return redirect()->back()->with('success', 'Laporan Berhasil Ditolak');
        }
    }

    public function responseIndex($id){
        $report = Report::findOrFail($id);

        return view('staff.response', compact('report'));
    }
}
