<?php

namespace App\Http\Controllers;

use App\Admin;
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
        if($request->isMethod('POST'))
        {
            $data = $request->input();

            $adminCount = Admin::where(['username' => $data['username'], 'password' => md5($data['password']), 'status' => '1'])->count();

            // if(Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'admin' => '1']))
            if($adminCount == 1)
            {
                Session::put('adminSession', $data['username']);

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

        return view('admin.dashboard');
    }

    // Settings Function

    public function settings()
    {
        $adminDetails = Admin::where(['username' => Session::get('adminSession')])->first();

        return view('admin.settings')->with(compact('adminDetails'));
    }

    // Check Password Function

    public function chkPassword(Request $request)
    {
        $data = $request->all();
        $adminCount = Admin::where(['username' => Session::get('adminSession'),'password'=>md5($data['current_pwd'])])->count();
        if ($adminCount == 1)
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
            $adminCount = Admin::where(['username' => Session::get('adminSession'),'password'=>md5($data['current_pwd'])])->count();

            if ($adminCount == 1)
            {
                $password = md5($data['new_pwd']);
                Admin::where('username',Session::get('adminSession'))->update(['password'=>$password]);

                return redirect('/admin/settings')->with('flash_message_success', 'Password updated successfully.');
            }
            else
            {
                return redirect('/admin/settings')->with('flash_message_error', 'Current Password entered is incorrect.');
            }
        }
    }

    // Logout Function

    public function logout()
    {
        Session::flush();

        return redirect('/admin')->with('flash_message_success', 'Logged out Successfully');
    }
}
