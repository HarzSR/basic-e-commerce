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

    public function payumoneyPayment(Request $keyArrayequest)
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

    public function payumoneyResponse(Request $keyArrayequest)
    {
        $keyArrayesponse = Indipay::response($keyArrayequest);

        if($keyArrayesponse['status'] == "success" && $keyArrayesponse['unmappedstatus'] == "captured")
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

    // Function to Verify Payment

    public function payumoneyVerification($order_id = null)
    {
        $key = 'gtKFFx';
        $salt = 'eCwWELxi';

        $command = "verify_payment";

        $temp_orderId = $order_id;
        $hash_str = $key  . '|' . $command . '|' . $temp_orderId . '|' . $salt ;
        $hash = strtolower(hash('sha512', $hash_str));
        $keyArray = array('key' => $key , 'hash' =>$hash , 'var1' => $temp_orderId, 'command' => $command);

        $query = http_build_query($keyArray);
        $wsUrl = "https://test.payu.in/merchant/postservice?form=2";

        $curlAction = curl_init();
        curl_setopt($curlAction, CURLOPT_URL, $wsUrl);
        curl_setopt($curlAction, CURLOPT_POST, 1);
        curl_setopt($curlAction, CURLOPT_POSTFIELDS, $query);
        curl_setopt($curlAction, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curlAction, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlAction, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curlAction, CURLOPT_SSL_VERIFYPEER, 0);
        $curlOutput = curl_exec($curlAction);

        if (curl_errno($curlAction))
        {
            $sad = curl_error($curlAction);
            throw new Exception($sad);
        }

        curl_close($curlAction);

        $valueSerialized = @unserialize($curlOutput);

        if($curlOutput === 'b:0;' || $valueSerialized !== false)
        {
            print_r($valueSerialized);
        }

        $curlOutput = json_decode($curlOutput);

        echo "<pre>"; print_r($curlOutput); die;
    }

    // Function to Verify

    public function payumoneyVerify()
    {
        $orders = Order::where('payment_method','Payumoney')->take(5)->orderBy('id','Desc')->get();
        $orders = json_decode(json_encode($orders));

        foreach($orders as $order)
        {
            $key = 'gtKFFx';
            $salt = 'eCwWELxi';
            $command = "verify_payment";
            $order_id = $order->id;
            $hash_str = $key  . '|' . $command . '|' . $order_id . '|' . $salt ;
            $hash = strtolower(hash('sha512', $hash_str));
            $keyArray = array('key' => $key , 'hash' =>$hash , 'var1' => $order_id, 'command' => $command);

            $query = http_build_query($keyArray);
            $wsUrl = "https://test.payu.in/merchant/postservice?form=2";

            $curlAction = curl_init();
            curl_setopt($curlAction, CURLOPT_URL, $wsUrl);
            curl_setopt($curlAction, CURLOPT_POST, 1);
            curl_setopt($curlAction, CURLOPT_POSTFIELDS, $query);
            curl_setopt($curlAction, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($curlAction, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curlAction, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curlAction, CURLOPT_SSL_VERIFYPEER, 0);
            $curlOutput= curl_exec($curlAction);

            if (curl_errno($curlAction))
            {
                $sad = curl_error($curlAction);
                throw new Exception($sad);
            }

            curl_close($curlAction);

            $valueSerialized = @unserialize($curlOutput);

            if($curlOutput=== 'b:0;' || $valueSerialized !== false)
            {
                print_r($valueSerialized);
            }

            $curlOutput= json_decode($curlOutput);

            foreach($curlOutput->transaction_details as $key => $val)
            {
                if(($val->status=="success")&&($val->unmappedstatus=="captured"))
                {
                    if($order->order_status == "Payment Failed" || $order->order_status == "New")
                    {
                        Order::where(['id' => $order->id])->update(['order_status' => 'Payment Captured']);
                    }
                }
                else
                {
                    if($order->order_status == "Payment Captured" || $order->order_status == "New")
                    {
                        Order::where(['id' => $order->id])->update(['order_status' => 'Payment Failed']);
                    }
                }
            }
        }

        echo "CRON Job Completed Successfully";
        die;
    }
}
