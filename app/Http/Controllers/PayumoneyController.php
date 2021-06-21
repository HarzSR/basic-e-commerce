<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Softon\Indipay\Facades\Indipay;

class PayumoneyController extends Controller
{
    // Function to Trigger PayUMoney

    public function payumoneyPayment(Request $request)
    {
        $parameters = [
            'txnid' => 123,
            'order_id' => 123,
            'amount' => 123.45,
            'firstname' => 'First',
            'lastname' => 'Last',
            'email' => 'email@email.com',
            'phone' => '001234567890',
            'productinfo' => 123,
            // 'service_provider' => '',
            'zipcode' => '123456',
            'city' => 'ABC',
            'state' => 'DEF',
            'country' => 'India',
            'address1' => 'GHI',
            'address2' => 'JKL',
            'curl' => url('payu/response'),
        ];
        $order = Indipay::prepare($parameters);

        return Indipay::process($order);
    }
}
