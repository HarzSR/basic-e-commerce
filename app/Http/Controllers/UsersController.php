<?php

namespace App\Http\Controllers;

use App\Country;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Session;
use DB;

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

                $email = $data['email'];
                $messageData = ['email' => $data['email'], 'name' => $data['name']];
                Mail::send('emails.register', $messageData, function($message) use($email) {
                    $message->to($email)->subject('Welcone to site');
                });

                if(Auth::attempt(['email' => $data['email'], 'password' => $data['registerPassword']]))
                {
                    Session::put('frontSession', $data['email']);

                    if(!empty(Session::has('session_id')))
                    {
                        $session_id = Session::get('session_id');
                        DB::table('cart')->where('session_id', $session_id)->update(['user_email' => $data['email']]);
                    }

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

                if(!empty(Session::has('session_id')))
                {
                    $session_id = Session::get('session_id');
                    DB::table('cart')->where('session_id', $session_id)->update(['user_email' => $data['email']]);
                }

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

    public function account(Request $request)
    {
        $user_id = Auth::user()->id;

        if($request->isMethod('POST'))
        {
            $data = $request->all();

            if(empty($data['name']))
            {
                return redirect()->back()->with('flash_message_error', 'User Name can not be empty.');
            }
            if(empty($data['address']))
            {
                $data['address'] = '';
            }
            if(empty($data['city']))
            {
                $data['city'] = '';
            }
            if(empty($data['state']))
            {
                $data['state'] = '';
            }
            if(empty($data['country']))
            {
                $data['country'] = '';
            }
            if(empty($data['pincode']))
            {
                $data['pincode'] = '';
            }
            if(empty($data['mobile']))
            {
                $data['mobile'] = '';
            }

            $user = User::find($user_id);
            $user->name = $data['name'];
            $user->address = $data['address'];
            $user->city = $data['city'];
            $user->state = $data['state'];
            $user->country = $data['country'];
            $user->pincode = $data['pincode'];
            $user->mobile = $data['mobile'];

            $user->save();

            return redirect()->back()->with('flash_message_success', 'Profile Update Successful.');
        }

        $userDetails = User::find($user_id);
        $countries = Country::get();

        return view('users.account')->with(compact('userDetails', 'countries'));
    }

    // Check User Password Function

    public function chkUserPassword(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $data = $request->all();

            $current_password = $data['current_pwd'];
            $user_id = Auth::User()->id;
            $check_password = User::where('id', $user_id)->first();

            if(Hash::check($current_password, $check_password->password))
            {
                echo "true";die;
            }
            else
            {
                echo "false";die;
            }
        }
    }

    // Update Password Function

    public function updatePassword(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $data = $request->all();

            $old_pwd = User::where('id', Auth::User()->id)->first();
            $current_pwd = $data['current_pwd'];

            if(Hash::check($current_pwd, $old_pwd->password))
            {
                $new_pwd = bcrypt($data['new_pwd']);
                User::where('id', Auth::User()->id)->update(['password' => $new_pwd]);

                return redirect()->back()->with('flash_message_success', 'Password Update Successful.');
            }
            else
            {
                return redirect()->back()->with('flash_message_error', 'Current Password is incorrect.');
            }
        }
    }

    // User Logout Function

    public function logout()
    {
        Session::forget('frontSession');
        Session::forget('session_id');

        // These will remove cart items

        // Session::flush();
        Auth::logout();

        return redirect('/');
    }
}
