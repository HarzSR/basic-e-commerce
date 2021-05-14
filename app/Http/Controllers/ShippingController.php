<?php

namespace App\Http\Controllers;

use App\ShippingCharge;
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

    // Delete Shipping Charge

    /*
    public function deleteShippingCharge($id = null)
    {
        ShippingCharge::where(['id' => $id])->delete();

        return redirect()->back()->with('flash_message_success', 'Shipping Charge Deleted Successfully');
    }
    */
}
