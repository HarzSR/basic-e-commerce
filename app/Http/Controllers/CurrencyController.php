<?php

namespace App\Http\Controllers;

use App\Currency;
use Validator;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    // Add Currency Function

    public function addCurrency(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $data = $request->all();

            $validator = Validator::make($request->all(), [
                'currency_code' => 'required|alpha|max:3',
                'exchange_rate' => 'required|numeric'
            ]);

            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput($request->input());
            }

            $currency = new Currency;
            $currency->currency_code = $data['currency_code'];
            $currency->exchange_rate = $data['exchange_rate'];
            if (empty($data['status']))
            {
                $currency->status = 0;
            }
            else
            {
                $currency->status = $data['status'];
            }

            $currency->save();

            return redirect()->back()->with('flash_message_success', 'Currency added Successfully');
        }

        return view('admin.currencies.add_currency');
    }

    // View Currency Function

    public function viewCurrency()
    {
        $currencies = Currency::get();
        $currencyCount = Currency::count();

        return view('admin.currencies.view_currencies')->with(compact('currencies', 'currencyCount'));
    }

    // Edit Currency Function

    public function editCurrency(Request $request, $id = null)
    {
        if($request->isMethod('POST'))
        {
            $data = $request->all();

            $validator = Validator::make($request->all(), [
                'currency_code' => 'required|alpha|max:3',
                'exchange_rate' => 'required|numeric'
            ]);

            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput($request->input());
            }

            if(empty($data['status']))
            {
                $status = 0;
            }
            else
            {
                $status = 1;
            }

            Currency::where('id', $id)->update(['currency_code' => $data['currency_code'], 'exchange_rate' => $data['exchange_rate'], 'status' => $status]);

            return redirect()->back()->with('flash_message_success', 'Currency Updated Successfully');
        }

        $currency = Currency::where('id', $id)->first();

        return view('admin.currencies.edit_currency')-> with(compact('currency'));
    }

    // Delete Currency Function

    public function deleteCurrency($id = null)
    {
        if(!empty($id))
        {
            Currency::where(['id' => $id])->delete();
            return redirect()->back()->with('flash_message_success', 'Successfully Deleted Currency');
        }
        else
        {
            return redirect()->back()->with('flash_message_error', 'Failed to Deleted Currency');
        }
    }
}
