<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index(){
        $user = User::get();
//        dd($user);
        return view('admin.home',[
            'title'=>'Trang quàn trị admin',
            'user'=>$user
        ]);
    }
}
