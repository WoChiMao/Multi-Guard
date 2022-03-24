<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function create(Request $request){
        $request -> validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|max:12',
            'cpassword' => 'required|same:password'
        ],[
            'cpassword.required' => 'The Confirm Password field is required!',
            'cpassword.same' => 'The Confirm Password and Password are not match!'
        ]);

        $user = new User();
        $user -> name = $request -> name;
        $user -> email = $request -> email;
        $user -> password = Hash::make($request -> password);
        $data = $user -> save();
        if ($data){
            return redirect()->back()->with('success', 'You have been registered successfully!');
        }
        else{
            return redirect()->back()->with('error', 'Registration faild!');
        }
    }

    public function dologin(Request $request){
        $request -> validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        $check = $request->only('email','password');

        if (Auth::guard('web')->attempt($check)){
            return redirect()->route('user.home')->with('success', 'Welcome to Dashboard!');
        }
        else{
            return redirect()->back()->with('error', 'Login failed!');
        }
    }

    public function logout(){
        Auth::guard('web')->logout();
        return redirect('/');
    }
}
