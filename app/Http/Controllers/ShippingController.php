<?php

namespace App\Http\Controllers;

use App\ShippingCharge;
use Validator;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    // View Shipping Charges

    public function viewShippingCharges()
    {
        $shipping_charges = ShippingCharge::get();
        $shipping_charges_count = ShippingCharge::count();

        return view('admin.shipping.view_shipping_charges')->with(compact('shipping_charges', 'shipping_charges_count'));
    }

    // Edit Shipping Charge

    public function editShippingCharge(Request $request, $id = null)
    {
        if($request->isMethod('POST'))
        {
            $data = $request->all();

            $validator = Validator::make($request->all(), [
                'shipping_charges' => 'required|numeric|between:0,999.99'
            ]);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput($request->input());
            }

            ShippingCharge::where('id', $id)->update(['shipping_charges' => $data['shipping_charges']]);

            return redirect()->back()->with('flash_message_success', 'Shipping Charge Updated Successfully');
        }

        $shipping_charge = ShippingCharge::where('id', $id)->first();

        return view('admin.shipping.edit_shipping_charge')->with(compact('shipping_charge'));
    }

    // Delete Shipping Charge

    /*
    public function deleteShippingCharge($id = null)
    {
        ShippingCharge::where(['id' => $id])->delete();

        return redirect()->back()->with('flash_message_success', 'Shipping Charge Deleted Successfully');
    }
    */
}
