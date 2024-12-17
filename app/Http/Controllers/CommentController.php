<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Report;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Report $report){
        $request->validate([
            'comment' => 'required',
        ]);

        Comment::create([
            'report_id' => $report->id,
            'user_id' => auth()->id(),
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan!');
    }
}
