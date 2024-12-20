<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Response;
use App\Models\StaffProvince;
use App\Models\User;
use ConsoleTVs\Charts\Facades\Charts;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;

class HeadStaffController extends Controller
{
    public function dashboard(){
        $headStaffProvince = auth()->user()->staffProvince;

        $provinceName = $headStaffProvince->province;

        $report = Report::where('province', $provinceName)->count();

        $response = Report::has('response')->count();

        return View('HeadStaff.chart', compact('report', 'response'));
    }

    public function create(){
        $users = User::with('staffProvince')
        ->where('role', 'STAFF')
        ->whereHas('staffProvince', function($query) {
            $query->where('province', auth()->user()->staffProvince->province);
        })->get();
        return view('HeadStaff.create', compact('users'));
    }

    public function store(Request $request) {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $users = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'STAFF',
        ]);

        StaffProvince::create([
            'user_id' => $users->id,
            'province' => strtoupper(auth()->user()->staffProvince->province),
        ]);

        return redirect()->route('headstaff.create', compact('users'));
    }
}
