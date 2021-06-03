<?php

namespace App\Http\Controllers;

use App\Exports\SubscribersExport;
use App\NewsletterSubscriber;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
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

    // Update Subscriber Function

    public function updateSubscriber($id = null, $status = null)
    {
        NewsletterSubscriber::where('id', $id)->update(['status' => $status]);

        if($status == 0)
        {
            return redirect()->back()->with('flash_message_success', 'Subscriber ' . $id . ' Unsubscribed Successfully');
        }
        if($status == 1)
        {
            return redirect()->back()->with('flash_message_success', 'Subscriber ' . $id . ' Subscribed Successfully');
        }
    }

    // Delete Subscriber Function

    public function deleteSubscriber($id = null)
    {
        NewsletterSubscriber::where('id', $id)->delete();

        return redirect('/admin/view-newsletter-subscribers')->with('flash_message_success', 'Subscriber Deleted Successfully');
    }

    // Export Subscribers Funciton

    public function exportSubscribers()
    {
        /*
        $subscribersData = NewsletterSubscriber::select('id', 'email', 'status', 'created_at')->orderBy('id')->get();

        return Excel::create('Newsletter Subscribers ' . date('d-M-Y h:i:s'), function ($excel) use ($subscribersData) {
            $excel->sheet('Data ' . date('d-M-Y h:i:s'), function ($sheet) use ($subscribersData) {
                $sheet->fromArray($subscribersData);
            });
        })->download('xlsx');
        */

        return Excel::download(new SubscribersExport, 'Newsletter Subscribers ' . date('d-M-Y h:i:s') . '.xlsx');
    }
}
