<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
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
            echo "Success";
        }
        else
        {
            echo "Fail";
        }
    }
}
