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
            $category->parent_id = $data['parent_id'];
            $category->description = $data['description'];
            $category->url = $data['url'];
            $category->save();
            return redirect('/admin/view-categories')->with('flash_message_success', 'Category added Successfully');
        }

        $levels = Category::where(['parent_id' => 0])->get();

        return view('admin.categories.add_category')->with(compact('levels'));
    }

    // View Category

    public function viewCategory()
    {
        $categories = Category::get();
        $levels = Category::where(['parent_id' => 0])->get();

        return view('admin.categories.view_categories')->with(compact('categories', 'levels'));
    }

    // Edit Category

    public function editCategory(Request $request, $id = null)
    {
        if($request->isMethod('POST'))
        {
            $data = $request->all();
            Category::where(['id' => $id])->update(['name' => $data['category_name'], 'description' => $data['description'], 'url' => $data['url'], 'parent_id' => $data['parent_id']]);
            return redirect('/admin/view-categories')->with('flash_message_success', 'Category Update Successful');
        }

        $categoryDetails = Category::where(['id' => $id])->first();
        $levels = Category::where(['parent_id' => 0])->get();

        return view('admin.categories.edit_category')->with(compact('categoryDetails', 'levels'));
    }

    // Delete Category

    public function deleteCategory($id = null)
    {
        if(!empty($id))
        {
            Category::where(['id' => $id])->delete();
            return redirect()->back()->with('flash_message_success', 'Successfully Deleted Category');
        }
        else
        {
            return redirect()->back()->with('flash_message_error', 'Failed to Deleted Category');
        }
    }
}
