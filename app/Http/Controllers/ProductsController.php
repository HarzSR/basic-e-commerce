<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Image;

class ProductsController extends Controller
{
    // Add Product Function

    public function addProduct(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $data = $request->all();

            $product = new Product();
            if(!empty($data['category_id']))
            {
                $product->category_id = $data['category_id'];
            }
            else
            {
                return redirect()->back()->with('flash_message_error', 'Category is missing');
            }
            if(!empty($data['product_name']))
            {
                $product->product_name = $data['product_name'];
            }
            else
            {
                return redirect()->back()->with('flash_message_error', 'Product Name is missing');
            }
            if(!empty($data['product_code']))
            {
                $product->product_code = $data['product_code'];
            }
            else
            {
                return redirect()->back()->with('flash_message_error', 'Product Code is missing');
            }
            if(!empty($data['product_color']))
            {
                $product->product_color = $data['product_color'];
            }
            else
            {
                return redirect()->back()->with('flash_message_error', 'Product Color is missing');
            }
            if(!empty($data['description']))
            {
                $product->description = $data['description'];
            }
            else
            {
                $product->description = '';
            }
            if(!empty($data['price']))
            {
                $product->price = $data['price'];
            }
            else
            {
                return redirect()->back()->with('flash_message_error', 'Product Price is missing');
            }
            if(empty($data['image']))
            {
                echo "Hai";
                $product->image = '';
            }
            else
            {
                if($request->hasFile('image'))
                {
                    $image_tmp = Input::file('image');

                    if($image_tmp->isValid())
                    {

                        $extension = $image_tmp->getClientOriginalExtension();
                        $fileName = time(). mt_rand() . '.' . $extension;

                        $large_image_path = 'images/backend_images/products/large/' . $fileName;
                        $medium_image_path = 'images/backend_images/products/medium/' . $fileName;
                        $small_image_path = 'images/backend_images/products/small/' . $fileName;

                        Image::make($image_tmp)->save($large_image_path);
                        Image::make($image_tmp)->resize(600, 600)->save($medium_image_path);
                        Image::make($image_tmp)->resize(300, 300)->save($small_image_path);

                        $product->image = $fileName;
                    }
                }
            }

            $product->save();

            return redirect()->back()->with('flash_message_success', 'Product Added Successfully');
        }

        $categories = Category::where(['parent_id' => 0])->get();
            $categories_dropdown = "<option value='' selected disable>Select</option>";

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

    // View Product Functsion

    public function viewProducts()
    {
        $products = Product::get();

        foreach ($products as $key => $val)
        {
            $category_name = Category::where(['id' => $val->category_id])->first();
            $products[$key]->category_name = $category_name->name;
        }

        return view('admin.products.view_products')->with(compact('products'));
    }
}
