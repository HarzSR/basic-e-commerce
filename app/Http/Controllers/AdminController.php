<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class AdminController extends Controller
{
    // Login Function

    public function login(Request $request)
    {
        // Check whether the method is requested as POST

        if($request->isMethod('POST'))
        {
            // Grab the sent input data

            $data = $request->input();

            // Attempt to validate against Email and Pass and also check for Admin

            if(Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'admin' => '1']))
            {
                return redirect('/admin/dashboard');
            }
            else
            {
                return redirect('/admin')->with('flash_message_error', 'Incorrect, please try again.');
            }
        }
        return view('admin.admin_login');
    }

    // Dashboard Function

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    // Logout Function

    public function logout()
    {
        Session::flush();
        return redirect('/admin')->with('flash_message_success', 'Logged out Successfully');
    }
}
