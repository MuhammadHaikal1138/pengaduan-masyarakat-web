<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Response;
use App\Models\ResponseProgress;
use Illuminate\Http\Request;

class ResponseController extends Controller
{
    public function response(Request $request, $id){
        $request->validate([
            'response_status' => 'required|in:ON_PROCESS,REJECT','DONE',
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
        $responses = Response::where('report_id',  $id)->with('responseProgress')->first();

        return view('staff.response', compact('report', 'responses'));
    }

    public function add(Request $request, Report $report){

        $request->validate([
            'response' => 'required',
        ]);

        $response = Response::where('report_id', $report->id)->first();
        ResponseProgress::create([
            'response_id' => $response->id,
            'histories' => $request->response, 
        ]);
        
        return redirect()->back()->with('success', 'Progress Berhasil Ditambahkan');
    }

    public function destroyProgress($id){

        ResponseProgress::where('id',  $id)->delete();
        return redirect()->back()->with([
            'success' => 'Berhasil Menghapus Progress',
        ]);
    }

    public function update($id){
       $response = Response::where('id', $id)->first();
         
       $response->update([
        'response_status' => 'DONE',
       ]);

       return redirect()->back()->with([
        'success' => 'pengaduan sudah diselesaikan'
       ]);
    }
}
