<?php

namespace App\Http\Controllers;

use App\Category;
use Validator;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Add Category

    public function addCategory(Request $request)
    {
        if(Session::get('adminDetails')['categories_access'] == 0)
        {
            return redirect('/admin/dashboard')->with('flash_message_error', 'Sorry, you don\'t have access to this page. How did you manage to come here. Please let us know, so that we can fix this bug.');
        }

        if($request->isMethod('POST'))
        {
            $data = $request->all();

            $validator = Validator::make($request->all(), [
                'category_name' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
                'parent_id' => 'required|numeric',
                'url' => 'required|regex:/^([a-z0-9]+-)*[a-z0-9]+$/i'
            ]);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput($request->input());
            }

            $category = new Category();
            $category->name = $data['category_name'];
            $category->parent_id = $data['parent_id'];
            $category->description = $data['description'];
            $category->url = $data['url'];
            if(empty($data['meta_title']))
            {
                $category->meta_title = "";
            }
            else
            {
                $category->meta_title = $data['meta_title'];
            }
            if(empty($data['status']))
            {
                $category->meta_description = "";
            }
            else
            {
                $category->meta_description = $data['meta_description'];
            }
            if(empty($data['status']))
            {
                $category->meta_keywords = "";
            }
            else
            {
                $category->meta_keywords = $data['meta_keywords'];
            }
            if(empty($data['status']))
            {
                $category->status = 0;
            }
            else
            {
                $category->status = 1;
            }
            $category->save();

            return redirect('/admin/view-categories')->with('flash_message_success', 'Category added Successfully');
        }

        $levels = Category::where(['parent_id' => 0])->get();

        return view('admin.categories.add_category')->with(compact('levels'));
    }

    // View Category

    public function viewCategory()
    {
        if(Session::get('adminDetails')['categories_access'] == 0)
        {
            return redirect('/admin/dashboard')->with('flash_message_error', 'Sorry, you don\'t have access to this page. How did you manage to come here. Please let us know, so that we can fix this bug.');
        }

        $categories = Category::get();
        $categoryCount = Category::count();
        $levels = Category::where(['parent_id' => 0])->get();

        return view('admin.categories.view_categories')->with(compact('categories', 'categoryCount', 'levels'));
    }

    // Edit Category

    public function editCategory(Request $request, $id = null)
    {
        if(Session::get('adminDetails')['categories_access'] == 0)
        {
            return redirect('/admin/dashboard')->with('flash_message_error', 'Sorry, you don\'t have access to this page. How did you manage to come here. Please let us know, so that we can fix this bug.');
        }

        if($request->isMethod('POST'))
        {
            $data = $request->all();

            $validator = Validator::make($request->all(), [
                'category_name' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
                'parent_id' => 'required|numeric',
                'url' => 'required|regex:/^([a-z0-9]+-)*[a-z0-9]+$/i'
            ]);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput($request->input());
            }

            if(empty($data['meta_title']))
            {
                $meta_title = "";
            }
            else
            {
                $meta_title = $data['meta_title'];
            }
            if(empty($data['meta_description']))
            {
                $meta_description = "";
            }
            else
            {
                $meta_description = $data['meta_description'];
            }
            if(empty($data['meta_keywords']))
            {
                $meta_keywords = "";
            }
            else
            {
                $meta_keywords = $data['meta_keywords'];
            }
            if(empty($data['status']))
            {
                $status = 0;
            }
            else
            {
                $status = 1;
            }
            Category::where(['id' => $id])->update(['name' => $data['category_name'], 'description' => $data['description'], 'url' => $data['url'], 'parent_id' => $data['parent_id'], 'meta_title' => $meta_title, 'meta_description' => $meta_description, 'meta_keywords' => $meta_keywords, 'status' => $status]);

            return redirect('/admin/view-categories')->with('flash_message_success', 'Category Update Successful');
        }

        $categoryDetails = Category::where(['id' => $id])->first();
        $levels = Category::where(['parent_id' => 0])->get();

        return view('admin.categories.edit_category')->with(compact('categoryDetails', 'levels'));
    }

    // Delete Category

    public function deleteCategory($id = null)
    {
        if(Session::get('adminDetails')['categories_access'] == 0)
        {
            return redirect('/admin/dashboard')->with('flash_message_error', 'Sorry, you don\'t have access to this page. How did you manage to come here. Please let us know, so that we can fix this bug.');
        }

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
