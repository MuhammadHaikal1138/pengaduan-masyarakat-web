<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function Login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            switch (Auth::user()->role){
                case 'GUEST':
                    return redirect()->route('report.article');
                case 'STAFF':
                    return redirect()->route('report.staff');
                case 'HEAD_STAFF':
                    return redirect()->route('head_staff.dashboard');
            }
        } else {
            return redirect()->back()->with('error', 'Gagal Login');
        }
    }

    public function PageRegist()
    {
        return view('Auth.regist');
    }

    public function Register(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::create([
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password'))
        ]);

        Auth::login($user);
        return redirect()->route('report.article');
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('Page.login');
    }
}
