<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class UsersController extends Controller
{
    // User Login View Function

    public function userLoginRegister()
    {
        return view('users.login_register');
    }

    // User Registration Function

    public function register(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $data = $request->all();

            $usersCount = User::where('email', $data['email'])->count();
            if($usersCount > 0)
            {
                return redirect()->back()->with('flash_message_error', 'User email already exist. Please sign in.');
            }
            else
            {
                $user = new User();
                $user->name = $data['name'];
                $user->email = $data['email'];
                $user->password = bcrypt($data['registerPassword']);
                $user->save();

                if(Auth::attempt(['email' => $data['email'], 'password' => $data['registerPassword']]))
                {
                    Session::put('frontSession', $data['email']);

                    return redirect('/');
                }

                // return redirect()->back()->with('flash_message_success', 'User Registration Successful. Please Login');
            }
        }
    }

    // Login User Function

    public function login(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $data = $request->all();

            if(Auth::attempt(['email' => $data['email'], 'password' => $data['loginPassword'], 'admin' => '0']))
            {
                Session::put('frontSession', $data['email']);

                return redirect('/');
            }
            else
            {
                return redirect()->back()->with('flash_message_error', 'Invalid Credentials. Please check Email and Password and try again.');
            }
        }
    }

    // Check Existing User Email Function

    public function checkEmail(Request $request)
    {
        $data = $request->all();

        $usersCount = User::where('email', $data['email'])->count();
        if($usersCount > 0)
        {
            echo "false";
        }
        else
        {
            echo "true";
        }
    }

    // User Account Function

    public function account()
    {
        return view('users.account');
    }

    // User Logout Function

    public function logout()
    {
        Session::forget('frontSession');
        Auth::logout();

        return redirect('/');
    }
}
