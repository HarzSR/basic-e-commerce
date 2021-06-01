<?php

namespace App\Http\Controllers;

use App\NewsletterSubscriber;
use Illuminate\Http\Request;
use Validator;

class NewsletterController extends Controller
{
    // Check Subscriber Function

    public function checkSubscriber(Request $request)
    {
        if($request->ajax())
        {
            $data = $request->all();

            $validator = Validator::make($request->all(), [
                'subscriber_email' => 'required|regex:/(.+)@(.+)\.(.+)/i'
            ]);

            if($validator->fails())
            {
                echo "Error";
            }
            else
            {
                $subscriberCount = NewsletterSubscriber::where('email', $data['subscriber_email'])->count();

                if($subscriberCount > 0)
                {
                    echo "Exist";
                }
                else
                {
                    echo "Success";
                }
            }
        }
    }
}
