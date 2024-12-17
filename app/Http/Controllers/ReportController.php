<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function PageArticle(Request $request){
        $province = $request->province;
        if($province){
            $reports = Report::where('province', $request->province)->doesntHave('response')->get();
        } else {
            $reports = Report::all();
        }
        return view('Report.article', compact('reports'));
    }

    public function PageArticleInfo($id){
        $comment = Comment::where('report_id', $id)->with('user')->get();
        $report = Report::find($id);

        $report->increment('viewers');
        return view('Report.article-info', compact('report', 'comment'));
    }

    public function ReportCreate(){
        return view('Report.create');
    }

    public function ReportStore(Request $request){
        $validatedData = $request->validate([
            'description' => 'required|string',
            'type' => 'required|in:KEJAHATAN,PEMBANGUNAN,SOSIAL',
            'province' => 'required|string',
            'regency' => 'required|string',
            'subdistrict' => 'required|string',
            'village' => 'required|string',
            'statement' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        Report::create([
            'user_id' => auth()->id(),
            'description' => $validatedData['description'],
            'type' => $validatedData['type'],
            'province' => $validatedData['province'],
            'regency' => $validatedData['regency'],
            'subdistrict' => $validatedData['subdistrict'],
            'village' => $validatedData['village'],
            'statement' => $validatedData['statement'],
            'image' => $request->file('image') ? $request->file('image')->store('reports', 'public') : null,
        ]);
        return redirect()->route('report.me')->with('success', 'Laporan berhasil dibuat!');
    }

    public function ReportMe(){
        $reports = Report::where('user_id', auth()->id())->get();
        return view('Report.me', compact('reports'));
    }

    public function destroy(Report $report){
        $report->delete();

        return redirect()->back()->with('success', 'Laporan Berhasil di Hapus');
    }

    public function vote(Request $request, $id){
        $report = Report::findOrFail($id);

        // Cek apakah voting null, jika iya, set default menjadi array kosong
        $voting = $report->voting ? json_decode($report->voting, true) : [];

        if ($request->action === 'like') {
            // Cek jika user sudah memberikan vote, maka tidak boleh duplicate
            if (!in_array(auth()->id(), $voting)) {
                $voting[] = auth()->id(); // Menambahkan user ID ke dalam array
            }
        } elseif ($request->action === 'unlike') {
            // Menghapus user ID jika ada
            $voting = array_diff($voting, [auth()->id()]);
        }

        // Update kolom voting dengan json_encode
        $report->voting = json_encode($voting);
        $report->save();

        // Mengembalikan jumlah vote (jumlah elemen dalam array)
        return response()->json(['voting' => count($voting)]);
    }
}
