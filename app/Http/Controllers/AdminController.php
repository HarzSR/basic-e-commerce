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
                // Add Session

                // Session::put('adminSession', $data['email']);

                // Redirect to Dashboard

                return redirect('/admin/dashboard');
            }
            else
            {
                // Redirect to Login with Error

                return redirect('/admin')->with('flash_message_error', 'Incorrect, please try again.');
            }
        }
        return view('admin.admin_login');
    }

    // Dashboard Function

    public function dashboard()
    {
        // Validate Session to provide access

        /*
        if(Session::has('adminSession'))
        {
            // Perform Admin Task
        }
        else
        {
            return redirect('/admin')->with('flash_message_error', 'Please Login to access data.');
        }
        */

        // Return Admin view

        return view('admin.dashboard');
    }

    // Settings Function

    public function settings()
    {
        // Return Settings View

        return view('admin.settings');
    }

    // Logout Function

    public function logout()
    {
        // Clear session

        Session::flush();

        // Redirect to Login with success message

        return redirect('/admin')->with('flash_message_success', 'Logged out Successfully');
    }
}
