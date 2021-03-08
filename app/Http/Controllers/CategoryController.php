<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Add Category

    public function addCategory(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $data = $request->all();
            $category = new Category();
            $category->name = $data['category_name'];
            $category->description = $data['description'];
            $category->url = $data['url'];
            $category->save();
        }

        return view('admin.categories.add_category');
    }
}
