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
            $cartCount = DB::table('cart')->where('user_email', $user_email)->sum('quantity');
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
}
