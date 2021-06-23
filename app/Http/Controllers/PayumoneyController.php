<?php

namespace App\Http\Controllers;

use App\Order;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Softon\Indipay\Facades\Indipay;

class PayumoneyController extends Controller
{
    // Function to Trigger PayUMoney

    public function payumoneyPayment(Request $request)
    {
        $order_id = Session::get('order_id');
        $grand_total = Session::get('grand_total');
        $orderDetails = Order::getOrderDetails($order_id);
        $fullName = explode(' ', $orderDetails->name);

        $parameters = [
            'txnid' => $order_id,
            'order_id' => $order_id,
            'amount' => $grand_total,
            'firstname' => $fullName[0],
            'lastname' => $fullName[0],
            'email' => $orderDetails->user_email,
            'phone' => $orderDetails->mobile,
            'productinfo' => $order_id,
            // 'service_provider' => '',
            'zipcode' => $orderDetails->pincode,
            'city' => $orderDetails->city,
            'state' => $orderDetails->state,
            'country' => $orderDetails->country,
            'address1' => $orderDetails->address,
            'address2' => '',
            'curl' => url('payumoney/response'),
        ];

        $order = Indipay::prepare($parameters);

        return Indipay::process($order);
    }

    // Function to PayUMoney Response

    public function payumoneyResponse(Request $request)
    {
        $response = Indipay::response($request);

        if($response['status'] == "success" && $response['unmappedstatus'] == "captured")
        {
            // echo "Success";

            $order_id = Session::get('order_id');

            Order::where('id', $order_id)->update(['order_status' => 'Paid-Success']);

            $productDetails = Order::with('orders')->where('id', $order_id)->first();
            $user_id = $productDetails['user_id'];
            $user_name = $productDetails['name'];
            $user_email = $productDetails['user_email'];
            $userDetails = User::where('id', $user_id)->first();

            // Email upon Successful Order

            /*
            $email = $user_email;
            $messageData = [
                'email' => $user_email,
                'name' => $user_name,
                'order_id' => $order_id,
                'productDetails' => $productDetails,
                'userDetails' => $userDetails
            ];
            Mail::send('emails.order', $messageData, function ($message) use($email) {
                $message->to($email)->subject('Order Placed & Paid Successfully');
            });
            */

            DB::table('cart')->where('user_email', $user_email)->delete();

            return redirect('/payumoney/thanks');
        }
        else
        {
            // echo "Fail";

            $order_id = Session::get('order_id');

            Order::where('id', $order_id)->update(['order_status' => 'Paid-Failure']);

            return redirect('/payumoney/failure');
        }
    }

    // Function for PayUMoney Success

    public function payumoneyThanks()
    {
        return view('orders.thanks_payumoney');
    }

    // Function for PayUMoney Failure

    public function payumoneyFailure()
    {
        return view('orders.failure_payumoney');
    }
}
