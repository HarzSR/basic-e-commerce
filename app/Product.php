<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class Product extends Model
{
    //

    public function attributes()
    {
        return $this->hasMany('App\ProductsAttribute', 'product_id');
    }

    // Get Cart Count Function

    public static function cartCount()
    {
        if(Auth::check())
        {
            $user_email = Auth::User()->email;
            $session_id = Session::get('session_id');
            $cartCount = DB::table('cart')->where(['user_email' => $user_email, 'session_id' => $session_id])->sum('quantity');
            // $cartCount = DB::table('cart')->where(['user_email' => $user_email])->sum('quantity');
        }
        else
        {
            $session_id = Session::get('session_id');
            $cartCount = DB::table('cart')->where('session_id', $session_id)->sum('quantity');
        }

        return $cartCount;
    }

    // Get Category Count Function

    public static function productCount($category_id)
    {
        $categoryCount = Product::where(['category_id' => $category_id, 'status' => 1])->count();

        return $categoryCount;
    }

    // Get Currency Function

    public static function getCurrencyRates($price)
    {
        $currencies = Currency::where('status',1)->get();

        foreach($currencies as $currency)
        {
            if($currency->currency_code == 'NZD')
            {
                $NZD_Rate = round($price/$currency->exchange_rate, 2);
            }
            else if($currency->currency_code == 'USD')
            {
                $USD_Rate = round($price/$currency->exchange_rate, 2);
            }
            else if($currency->currency_code == 'GBP')
            {
                $GBP_Rate = round($price/$currency->exchange_rate, 2);
            }
            else if($currency->currency_code == 'EUR')
            {
                $EUR_Rate = round($price/$currency->exchange_rate, 2);
            }
            else if($currency->currency_code == 'INR')
            {
                $INR_Rate = round($price/$currency->exchange_rate, 2);
            }
        }

        $currenciesArray = array('NZD_Rate' => $NZD_Rate, 'USD_Rate' => $USD_Rate, 'GBP_Rate' => $GBP_Rate, 'EUR_Rate' => $EUR_Rate, 'INR_Rate' => $INR_Rate);

        return $currenciesArray;
    }

    // Get Product Stock Function

    public static function getProductStock($product_id, $product_size)
    {
        $getAttributeCount = self::getAttributeCount($product_id, $product_size);

        if($getAttributeCount > 0)
        {
            $getProductStock = ProductsAttribute::select('stock')->where(['product_id' => $product_id, 'size' => $product_size])->first();
            $productStock = $getProductStock->stock;
        }
        else
        {
            $productStock = 0;
        }

        return $productStock;
    }

    // Delete Cart Item of 0 Stock Product Function

    public static function deleteCartProduct($product_id, $user_email)
    {
        DB::table('cart')->where(['product_id' => $product_id, 'user_email' => $user_email])->delete();
    }

    // Get Product Status Function

    public static function getProductStatus($product_id)
    {
        $getProductStatus = Product::select('status')->where('id', $product_id)->first();

        return $getProductStatus->status;
    }

    // Get Attributes Count Function

    public static function getAttributeCount($product_id, $product_size)
    {
        $getAttributeCount = ProductsAttribute::where(['product_id' => $product_id, 'size' => $product_size])->count();

        return $getAttributeCount;
    }

    // Get Category Status Function

    public static function getCategoryStatus($category_id)
    {
        $getCategoryStatus = Category::where('id', $category_id)->count();

        if($getCategoryStatus > 0)
        {
            $categoryStatus = Category::select('status')->where('id', $category_id)->first();
            $status = $categoryStatus->status;
        }

        return $status;
    }

    // Get Shipping Charges Function

    public static function getShippingCharges($total_weight, $country)
    {
        $shippingDetails = ShippingCharge::where('country', $country)->first();

        if($total_weight > 0)
        {
            if($total_weight > 0 && $total_weight <= 500)
            {
                $shipping_charges = $shippingDetails->shipping_charges_0_500g;
            }
            else if($total_weight > 500 && $total_weight <= 1000)
            {
                $shipping_charges = $shippingDetails->shipping_charges_501_1000g;
            }
            else if($total_weight > 1000 && $total_weight <= 2000)
            {
                $shipping_charges = $shippingDetails->shipping_charges_1001_2000g;
            }
            else if($total_weight > 2000 && $total_weight <= 5000)
            {
                $shipping_charges = $shippingDetails->shipping_charges_2001_5000g;
            }
            else
            {
                $shipping_charges = 10000;
            }
        }
        else
        {
            $shipping_charges = 0;
        }

        return $shipping_charges;
    }

    // Get Grand Total Function

    public static function getGrandTotal()
    {
        $user_email = Auth::User()->email;
        $session_id = Session::get('session_id');
        $userCart = DB::table('cart')->where(['user_email' => $user_email, 'session_id' => $session_id])->get();
        $userCartCount = DB::table('cart')->where(['user_email' => $user_email, 'session_id' => $session_id])->count();

        if($userCartCount == 1)
        {
            $productPrice = ProductsAttribute::where(['product_id' => $userCart[0]->product_id, 'size' => $userCart[0]->sizex])->first();
            $priceArray[] = $productPrice->price;
        }
        else
        {
            foreach ($userCart as $product)
            {
                $productPrice = ProductsAttribute::where(['product_id' => $product['product_id'], 'size' => $product['size']])->first();
                $priceArray[] = $productPrice->price;
            }
        }

        $grandTotal = array_sum($priceArray) - Session::get('couponAmount') + Session::get('ShippingCharges');

        return $grandTotal;
    }

    // Get Product Price Function

    public static function getProductPrice($product_id, $product_size)
    {
        $getProductPrice = ProductsAttribute::select('price')->where(['product_id' => $product_id, 'size' => $product_size])->first();

        return $getProductPrice->price;
    }
}
