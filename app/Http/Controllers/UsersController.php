<?php

namespace App\Http\Controllers;

use App\Country;
use App\Exports\usersExport;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Session;
use DB;
use function foo\func;

class UsersController extends Controller
{
    // User Login View Function

    public function userLoginRegister()
    {
        $meta_title = "Sign up/Sign in";
        $meta_description = "Log in to access the full power of shopping";
        $meta_keywords = "shopping";

        return view('users.login_register')->with(compact('meta_title', 'meta_description', 'meta_keywords'));
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
                // date_default_timezone_set('Asia/Kolkata');
                // $user->created_at = date('Y-m-d H:i:s');
                // $user->updated_at = date('Y-m-d H:i:s');
                $user->save();

                // Send Successful Registration Email

                /*
                $email = $data['email'];
                $messageData = ['email' => $data['email'], 'name' => $data['name']];
                Mail::send('emails.register', $messageData, function($message) use($email) {
                    $message->to($email)->subject('Welcome to site');
                });
                */

                // Email Confirmation

                /*
                $email = $data['email'];
                $messageData = ['email' => $data['email'], 'name' => $data['name'], 'code' => base64_encode($data['email'])];
                Mail::send('emails.confirmation', $messageData, function($message) use($email) {
                    $message->to($email)->subject('Please verify your account');
                });
                */

                return redirect()->back()->with('flash_message_success', 'User Successfully Registered. Please check email for verification link.');

                // Register and Login in by Default

                /*
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
                */

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

            // if(Auth::attempt(['email' => $data['email'], 'password' => $data['loginPassword'], 'admin' => '0']))
            if(Auth::attempt(['email' => $data['email'], 'password' => $data['loginPassword']]))
            {
                $userStatus = User::where('email', $data['email'])->first();

                if($userStatus->status == 0)
                {
                    return redirect()->back()->with('flash_message_error', 'Your account is not activated. Please check your emails for activation link.')->withInput($request->input());
                }
                if($userStatus->status == 2)
                {
                    return redirect()->back()->with('flash_message_error', 'Your Account has been Deactivated. Please contact Support for further Assistance');
                }
                if($userStatus->status == 3)
                {
                    return redirect()->back()->with('flash_message_error', 'Your account has been suspended for violating our Terms & Condition. We are sorry, but we can not retrieve your account.');
                }
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

    // User Email Confirmation

    public function confirmAccount($email = null)
    {
        $email = base64_decode($email);
        $userCount = User::where('email', $email)->count();

        If($userCount == 1)
        {
            $userDetails = User::where('email', $email)->first();

            if($userDetails->status == 1)
            {
                return redirect('login-register')->with('flash_message_success', 'User Already Registered.');
            }
            else
            {
                User::where('email', $email)->update(['status' => 1]);

                // Send Successful Registration Email

                /*
                $messageData = ['email' => $email, 'name' => $userDetails->name];
                Mail::send('emails.welcome', $messageData, function($message) use($email) {
                    $message->to($email)->subject('Welcome to site');
                });
                */

                return redirect('login-register')->with('flash_message_success', 'Successfully Activated. Please Login.');
            }
        }
        else
        {
            abort(404);
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

    // Admin View Users Function

    public function viewUsers()
    {
        if(Session::get('adminDetails')['users_view_access'] == 0 && Session::get('adminDetails')['users_edit_access'] == 0 && Session::get('adminDetails')['users_full_access'] == 0)
        {
            return redirect('/admin/dashboard')->with('flash_message_error', 'Sorry, you don\'t have access to this page. How did you manage to come here. Please let us know, so that we can fix this bug.');
        }

        $users = User::get();
        $userCount = User::count();

        return view('admin.users.view_users')->with(compact('users', 'userCount'));
    }

    // Forgot Password for Users Function

    public function forgotPassword(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $data = $request->all();

            $userCount = User::where('email', $data['email'])->count();

            if($userCount == 0)
            {
                return redirect()->back()->with('flash_message_error', 'Email doesn\'t exist in the system. Please check the spelling and try again.');
            }

            $userDetails = User::where('email', $data['email'])->first();
            $random_password = str_random(10);
            $new_password = bcrypt($random_password);

            User::where('email', $data['email'])->update(['password' => $new_password]);

            // Send Forgotten Email

            /*
            $email = $data['email'];
            $name = $userDetails->name;
            $messageData = [
                'email' => $email,
                'name' => $name,
                'password' => $random_password
            ];
            Mail::send('emails.forgotpassword', $messageData, function ($message) use ($email){
                $message->to($email)->subject('New Temporary Password');
            });
            */

            return redirect('/login-register')->with('flash_message_success', 'Password reset Successful. Please check email for Temporary New Password');
        }

        return view('users.forgot_password');
    }

    // Export Users function

    public function exportUsers()
    {
        return Excel::download(new usersExport,'Users ' . date('d-M-Y h:i:s') . '.xlsx');
    }

    // View User Analysis Function

    public function viewUsersAnalysis()
    {
        $userCount = User::count();

        return view('admin.users.view_users_analysis')->with(compact('userCount'));
    }
}
