<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    //

    public function addProduct(Request $request)
    {
        $categories = Category::where(['parent_id' => 0])->get();
        $categories_dropdown = "<option selected disable>Select</option>";

        foreach($categories as $category)
        {
            $categories_dropdown .= "<option value='" . $category->id . "' >" . $category->name . "</option>";
            $sub_categories = Category::where(['parent_id' => $category->id])->get();

            foreach($sub_categories as $sub_category)
            {
                $categories_dropdown .= "<option value='" . $sub_category->id . "'> &nbsp; --&nbsp;" . $sub_category->name . "</option>";
            }
        }

        return view('admin.products.add_product')->with(compact('categories_dropdown'));
    }
}
