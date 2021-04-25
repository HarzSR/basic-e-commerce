<?php

namespace App\Http\Controllers;

use App\Category;
use App\Coupon;
use Illuminate\Http\Request;

class CouponsController extends Controller
{
    // Add Coupon Function

    public function addCoupon(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $data = $request->all();
            $data['expiry_date'] = date('Y-m-d', strtotime($data['expiry_date']));

            $coupon = new Coupon;
            $coupon->coupon_code = $data['coupon_code'];
            $coupon->amount = $data['amount'];
            $coupon->amount_type = $data['amount_type'];
            $coupon->expiry_date = $data['expiry_date'];
            if (empty($data['status'])) {
                $coupon->status = 0;
            } else {
                $coupon->status = $data['status'];
            }
            $coupon->save();

            // return redirect('/admin/add-coupon')->with('flash_message_success', 'Coupon Added Successfully');
            return redirect()->action('CouponsController@viewCoupons')->with('flash_message_success', 'Coupon Added Successfully');
        }

        return view('admin.coupons.add_coupon');
    }

    // View Coupons Function

    public function viewCoupons()
    {
        $coupons = Coupon::get();
        $couponCount = Coupon::count();

        return view('admin.coupons.view_coupons')->with(compact('coupons', 'couponCount'));
    }

    // Edit Coupon Function

    public function editCoupon(Request $request, $id = null)
    {
        if($request->isMethod('POST'))
        {
            $data = $request->all();
            $data['expiry_date'] = date('Y-m-d', strtotime($data['expiry_date']));

            $coupon = Coupon::find($id);
            $coupon->coupon_code = $data['coupon_code'];
            $coupon->amount = $data['amount'];
            $coupon->amount_type = $data['amount_type'];
            $coupon->expiry_date = $data['expiry_date'];
            if (empty($data['status'])) {
                $coupon->status = 0;
            } else {
                $coupon->status = $data['status'];
            }
            $coupon->save();

            return redirect()->action('CouponsController@viewCoupons')->with('flash_message_success', 'Coupon Updated Successfully');
        }

        $couponDetails = Coupon::find($id);

        return view('admin.coupons.edit_coupon')->with(compact('couponDetails'));
    }

    // Delete Coupon Function

    public function deleteCoupon($id = null)
    {
        Coupon::where(['id' => $id])->delete();

        return redirect()->back()->with('flash_message_success', 'Coupon Deleted Successfully');
    }
}
