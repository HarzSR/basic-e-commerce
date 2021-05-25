<?php

namespace App\Http\Middleware;

use App\Admin;
use Closure;
use Illuminate\Support\Facades\Route;
use Session;

class Adminlogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(empty(Session::has('adminSession')))
        {
            return redirect('/admin');
        }
        else
        {
            $adminDetails = Admin::where('username', Session::get('adminSession'))->first();

            Session::put('adminDetails', $adminDetails);

            $currentPath = Route::getFacadeRoot()->current()->uri();

            if(($currentPath == "admin/add-category" || $currentPath == "admin/edit-category/{id}") && Session::get('adminDetails')['categories_edit_access'] == 0 && Session::get('adminDetails')['categories_full_access'] == 0)
            {
                return redirect('/admin/dashboard')->with('flash_message_error', 'Sorry, unfortunately you don\'t have access to this module. Please contact Admin for further access.');
            }
            if($currentPath == "admin/view-categories" && Session::get('adminDetails')['categories_view_access'] == 0 && Session::get('adminDetails')['categories_edit_access'] == 0 && Session::get('adminDetails')['categories_full_access'] == 0)
            {
                return redirect('/admin/dashboard')->with('flash_message_error', 'Sorry, unfortunately you don\'t have access to this module. Please contact Admin for further access.');
            }
            if($currentPath == "admin/delete-category/{id}" && Session::get('adminDetails')['categories_full_access'] == 0)
            {
                return redirect('/admin/dashboard')->with('flash_message_error', 'Sorry, unfortunately you don\'t have access to this module. Please contact Admin for further access.');
            }
            if(($currentPath == "admin/add-product" || $currentPath == "admin/view-products" || $currentPath == "admin/edit-product/{id}" || $currentPath == "admin/delete-product/{id}" || $currentPath == "admin/add-attributes/{id}" || $currentPath == "admin/edit-attributes/{id}" || $currentPath == "admin/add-images/{id}" || $currentPath == "admin/delete-additional-image/{id}" || $currentPath == "admin/delete-attribute/{id}") && Session::get('adminDetails')['products_access'] == 0)
            {
                return redirect('/admin/dashboard')->with('flash_message_error', 'Sorry, unfortunately you don\'t have access to this module. Please contact Admin for further access.');
            }
            if(($currentPath == "admin/view-orders" || $currentPath == "admin/view-order/{id}" || $currentPath == "admin/view-order-invoice/{id}" || $currentPath == "admin/print-order-invoice/{id}" || $currentPath == "admin/update-order-status") && Session::get('adminDetails')['orders_access'] == 0)
            {
                return redirect('/admin/dashboard')->with('flash_message_error', 'Sorry, unfortunately you don\'t have access to this module. Please contact Admin for further access.');
            }
            if($currentPath == "admin/view-users" && Session::get('adminDetails')['users_access'] == 0)
            {
                return redirect('/admin/dashboard')->with('flash_message_error', 'Sorry, unfortunately you don\'t have access to this module. Please contact Admin for further access.');
            }
        }

        return $next($request);
    }
}
