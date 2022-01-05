<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function index(){
        $user = User::get();
        return view('admin.users.login',[
            'user'=>$user
        ]);
    }
    public function store(Request $request){
        $this->validate($request,[
            'email'     => 'required|email:filter',
           'password'   => 'required'
        ]);
//        dd($request->input());
        if(Auth::attempt([
            'email' =>$request->input('email'),
            'password' => $request->input('password'),
            ],$request->input('remember'))) {
           return redirect()->route('admin');
        }
        Session::flash('error','Email hoặc password không chính xác');
        return redirect()->back();
    }

}
