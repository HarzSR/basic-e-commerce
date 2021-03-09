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
            return redirect('/admin/view-categories')->with('flash_message_success', 'Category added Successfully');
        }

        return view('admin.categories.add_category');
    }

    // View Category

    public function viewCategory()
    {
        $categories = Category::get();
        return view('admin.categories.view_categories')->with(compact('categories'));
    }
}
