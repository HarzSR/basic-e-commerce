<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\User;
use Illuminate\Support\Facades\Hash;

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

    // Check Password Function

    public function chkPassword(Request $request)
    {
        $data = $request->all();
        $current_password = $data['current_pwd'];
        $check_password = User::where(['admin'=>'1'],['email' => Auth::user()->email])->first();
        if(Hash::check($current_password,$check_password->password))
        {
            echo "true"; die;
        }
        else
        {
            echo "false"; die;
        }
    }

    // Update Password Function

    public function updatePassword(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $data = $request->all();
            $check_password = User::where(['email' => Auth::user()->email])->first();
            $current_password = $data['current_pwd'];
            if(Hash::check($current_password,$check_password->password))
            {
                $password = bcrypt($data['new_pwd']);
                User::where(['email' => Auth::user()->email])->update(['password' => $password]);
                return redirect('/admin/settings')->with('flash_message_success', 'Password update Successful.');
            }
            else
            {
                return redirect('/admin/settings')->with('flash_message_error', 'Incorrect current password');
            }
        }
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
