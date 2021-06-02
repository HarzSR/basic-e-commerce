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

    // Add Subscriber Function

    public function addSubscriber(Request $request)
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
                    $newsLetter = new NewsletterSubscriber;
                    $newsLetter->email = $data['subscriber_email'];
                    $newsLetter->status = 1;
                    $newsLetter->save();

                    echo "Success";
                }
            }
        }
    }

    // View Subscribers Function

    public function viewSubscribers()
    {
        $subscribers = NewsletterSubscriber::get();
        $subscriberCount = NewsletterSubscriber::count();

        return view('admin.newsletters.view_newsletter_subscribers')->with(compact('subscribers', 'subscriberCount'));
    }
}
