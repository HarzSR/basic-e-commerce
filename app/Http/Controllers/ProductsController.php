<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\ProductsAttribute;
use App\ProductsImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Image;

class ProductsController extends Controller
{
    // Add Product Function

    public function addProduct(Request $request)
    {
        if ($request->isMethod('POST')) {
            $data = $request->all();

            $product = new Product();
            if (!empty($data['category_id'])) {
                $product->category_id = $data['category_id'];
            } else {
                return redirect()->back()->with('flash_message_error', 'Category is missing');
            }
            if (!empty($data['product_name'])) {
                $product->product_name = $data['product_name'];
            } else {
                return redirect()->back()->with('flash_message_error', 'Product Name is missing');
            }
            if (!empty($data['product_code'])) {
                $product->product_code = $data['product_code'];
            } else {
                return redirect()->back()->with('flash_message_error', 'Product Code is missing');
            }
            if (!empty($data['product_color'])) {
                $product->product_color = $data['product_color'];
            } else {
                return redirect()->back()->with('flash_message_error', 'Product Color is missing');
            }
            if (!empty($data['description'])) {
                $product->description = $data['description'];
            } else {
                $product->description = '';
            }
            if (!empty($data['care'])) {
                $product->care = $data['care'];
            } else {
                $product->care = '';
            }
            if (!empty($data['price'])) {
                $product->price = $data['price'];
            } else {
                return redirect()->back()->with('flash_message_error', 'Product Price is missing');
            }
            if (empty($data['image'])) {
                $product->image = '';
            } else {
                if ($request->hasFile('image')) {
                    $image_tmp = Input::file('image');

                    if ($image_tmp->isValid()) {

                        $extension = $image_tmp->getClientOriginalExtension();
                        $fileName = time() . mt_rand() . '.' . $extension;

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
            if (empty($data['status'])) {
                $product->status = 0;
            } else {
                $product->status = 1;
            }

            $product->save();

            return redirect()->back()->with('flash_message_success', 'Product Added Successfully');
        }

        $categories = Category::where(['parent_id' => 0])->get();
        $categories_dropdown = "<option value='' selected disabled>Select</option>";

        foreach ($categories as $category) {
            $categories_dropdown .= "<option value='" . $category->id . "' >" . $category->name . "</option>";
            $sub_categories = Category::where(['parent_id' => $category->id])->get();

            foreach ($sub_categories as $sub_category) {
                $categories_dropdown .= "<option value='" . $sub_category->id . "'> &nbsp; --&nbsp;" . $sub_category->name . "</option>";
            }
        }

        return view('admin.products.add_product')->with(compact('categories_dropdown'));
    }

    // View Product Function

    public function viewProducts()
    {
        $products = Product::orderby('id', 'DESC')->get();

        foreach ($products as $key => $val) {
            $category_name = Category::where(['id' => $val->category_id])->first();
            $products[$key]->category_name = $category_name->name;
        }

        return view('admin.products.view_products')->with(compact('products'));
    }

    // Edit Product Function

    public function editProduct(Request $request, $id = null)
    {
        if ($request->isMethod('POST')) {
            $data = $request->all();

            if ($request->hasFile('image')) {
                $image_tmp = Input::file('image');

                if ($image_tmp->isValid()) {

                    $extension = $image_tmp->getClientOriginalExtension();
                    $fileName = time() . mt_rand() . '.' . $extension;

                    $large_image_path = 'images/backend_images/products/large/' . $fileName;
                    $medium_image_path = 'images/backend_images/products/medium/' . $fileName;
                    $small_image_path = 'images/backend_images/products/small/' . $fileName;

                    Image::make($image_tmp)->save($large_image_path);
                    Image::make($image_tmp)->resize(600, 600)->save($medium_image_path);
                    Image::make($image_tmp)->resize(300, 300)->save($small_image_path);
                }
            } else {
                if (empty($data['current_image'])) {
                    $fileName = "";
                } else {
                    $fileName = $data['current_image'];
                }
            }

            if (empty($data['description'])) {
                $data['description'] = '';
            }

            if (empty($data['care'])) {
                $data['care'] = '';
            }
            if (empty($data['status'])) {
                $status = 0;
            } else {
                $status = 1;
            }

            Product::where(['id' => $id])->update(['category_id' => $data['category_id'], 'product_name' => $data['product_name'], 'product_code' => $data['product_code'], 'product_color' => $data['product_color'], 'description' => $data['description'], 'care' => $data['care'], 'price' => $data['price'], 'image' => $fileName, 'status' => $status]);

            return redirect()->back()->with('flash_message_success', 'Product Updated Successfully');
        }

        $productDetails = Product::where(['id' => $id])->first();

        $categories = Category::where(['parent_id' => 0])->get();
        $categories_dropdown = "<option value='' selected disabled>Select</option>";

        foreach ($categories as $category) {
            if ($category->id == $productDetails->category_id) {
                $selectVariable = "selected";
            } else {
                $selectVariable = "";
            }

            $categories_dropdown .= "<option value='" . $category->id . "' " . $selectVariable . ">" . $category->name . "</option>";
            $sub_categories = Category::where(['parent_id' => $category->id])->get();

            foreach ($sub_categories as $sub_category) {
                if ($sub_category->id == $productDetails->category_id) {
                    $selectVariable = "selected";
                } else {
                    $selectVariable = "";
                }

                $categories_dropdown .= "<option value='" . $sub_category->id . "' " . $selectVariable . "> &nbsp; --&nbsp; " . $sub_category->name . "</option>";
            }
        }

        return view('admin.products.edit_product')->with(compact('productDetails', 'categories_dropdown'));
    }

    // Delete Product Function

    public function deleteProduct($id = null)
    {
        Product::where(['id' => $id])->delete();

        return redirect()->back()->with('flash_message_success', 'Product removed successfully');
    }

    // Delete Product Image Function

    public function deleteProductImage($id = null)
    {
        // Hard Delete

        $product = Product::where(['id' => $id])->first();

        $large_image_path = 'images/backend_images/products/large/' . $product->image;
        $medium_image_path = 'images/backend_images/products/medium/' . $product->image;
        $small_image_path = 'images/backend_images/products/small/' . $product->image;

        // File::delete($large_image_path, $medium_image_path, $small_image_path);
        if (file_exists($large_image_path)) {
            unlink($large_image_path);
        }
        if (file_exists($medium_image_path)) {
            unlink($medium_image_path);
        }
        if (file_exists($small_image_path)) {
            unlink($small_image_path);
        }

        // Soft Delete

        Product::where(['id' => $id])->update(['image' => '']);

        return redirect()->back()->with('flash_message_success', 'Product Image Removed Successfully');
    }

    // Add Product Attribute Function

    public function addAttributes(Request $request, $id = null)
    {
        if ($request->isMethod('POST')) {
            $data = $request->all();
            foreach ($data['sku'] as $key => $val) {
                if (!empty($val)) {
                    $attributeSkuCheck = ProductsAttribute::where('sku', $val)->count();
                    if ($attributeSkuCheck > 0) {
                        return redirect('/admin/add-attributes/' . $id)->with('flash_message_error', 'SKU \"' . $val . '\" already existing');
                    }
                    $attributeSizeCheck = ProductsAttribute::where(['product_id' => $id, 'size' => $data['size'][$key]])->count();
                    if ($attributeSizeCheck > 0) {
                        return redirect('/admin/add-attributes/' . $id)->with('flash_message_error', 'Size \"' . $data['size'][$key] . '\" already existing');
                    }

                    $attribute = new ProductsAttribute;
                    $attribute->product_id = $id;
                    $attribute->sku = $val;
                    $attribute->size = $data['size'][$key];
                    $attribute->price = $data['price'][$key];
                    $attribute->stock = $data['stock'][$key];
                    $attribute->save();
                }
            }

            return redirect('/admin/add-attributes/' . $id)->with('flash_message_success', 'Product Attributes Added Successfully');
        }

        $productDetails = Product::with('attributes')->where(['id' => $id])->first();

        return view('admin.products.add_attributes')->with(compact('productDetails'));
    }

    // Edit Attributes Function

    public function editAttributes(Request $request, $id = null)
    {
        if ($request->isMethod('POST')) {
            $data = $request->all();
            foreach ($data['idAttr'] as $key => $attr) {
                ProductsAttribute::where(['id' => $data['idAttr'][$key]])->update(['price' => $data['price'][$key], 'stock' => $data['stock'][$key]]);
            }

            return redirect()->back()->with('flash_message_success', 'Products Attribute Updated Successfully');
        }
    }

    // Add More Images Function

    public function addImages(Request $request, $id = null)
    {
        if ($request->isMethod('POST')) {
            $data = $request->all();
            if ($request->hasFile('image')) {
                $files = $request->file('image');
                foreach ($files as $file) {
                    if ($file->isValid()) {
                        $image = new ProductsImage;

                        $extension = $file->getClientOriginalExtension();
                        $fileName = time() . mt_rand() . '.' . $extension;

                        $large_image_path = 'images/backend_images/products/large/' . $fileName;
                        $medium_image_path = 'images/backend_images/products/medium/' . $fileName;
                        $small_image_path = 'images/backend_images/products/small/' . $fileName;

                        Image::make($file)->save($large_image_path);
                        Image::make($file)->resize(600, 600)->save($medium_image_path);
                        Image::make($file)->resize(300, 300)->save($small_image_path);

                        $image->image = $fileName;
                        $image->product_id = $id;
                        $image->save();
                    }
                }
            }

            return redirect()->back()->with('flash_message_success', 'Images Added Successfully');
        }

        $productDetails = Product::with('attributes')->where(['id' => $id])->first();

        $productsImages = ProductsImage::where(['product_id' => $id])->get();

        return view('admin.products.add_images')->with(compact('productDetails', 'productsImages'));
    }

    // Delete Product Attribute Function

    public function deleteAttribute($id = null)
    {
        ProductsAttribute::where(['id' => $id])->delete();

        return redirect()->back()->with('flash_message_success', 'Product Attribute Deleted Successfully');
    }

    // Display Products Function

    public function products($url = null)
    {
        $categoryCount = Category::where(['url' => $url, 'status' => 1])->count();
        if ($categoryCount == 0) {
            abort(404);
        }

        $categories = Category::with('categories')->where(['parent_id' => 0])->get();

        $categoryDetails = Category::where(['url' => $url])->first();

        if ($categoryDetails->parent_id != 0) {
            $productsAll = Product::where(['category_id' => $categoryDetails->id])->where('status', 1)->get();
        } else {
            $subCategories = Category::where(['parent_id' => $categoryDetails->id])->get();
            foreach ($subCategories as $subCategory) {
                $categoryIds[] = $subCategory->id;
            }
            array_push($categoryIds, $categoryDetails->id);
            $productsAll = Product::whereIn('category_id', $categoryIds)->where('status', 1)->get();
        }

        return view('products.listing')->with(compact('categoryDetails', 'productsAll', 'categories'));
    }

    // Display Individual Product Function

    public function product($id = null)
    {
        $productsCount = Product::where(['id' => $id, 'status' => 1])->count();
        if ($productsCount == 0) {
            abort(404);
        }

        $productDetails = Product::with('attributes')->where(['id' => $id])->first();

        $categories = Category::with('categories')->where(['parent_id' => 0])->get();

        $productAdditionalImages = ProductsImage::where('product_id', $id)->get();

        $total_stock = ProductsAttribute::where('product_id', $id)->sum('stock');

        $relatedProducts = Product::where('id', '!=', $id)->where(['category_id' => $productDetails->category_id])->get();

        return view('products.detail')->with(compact('productDetails', 'categories', 'productAdditionalImages', 'total_stock', 'relatedProducts'));
    }

    // Get Product Price upon Attribute Change Function

    public function getProductPrice(Request $request)
    {
        $data = $request->all();

        $sizeArray = explode('-', $data['idSize']);
        $productAttribute = ProductsAttribute::where(['product_id' => $sizeArray[0], 'size' => $sizeArray[1]])->first();
        echo $productAttribute->price . '#' . $productAttribute->stock;
    }

    // Delete Additional Image Function

    public function deleteAdditionalImage($id = null)
    {
        // Hard Delete

        $product = ProductsImage::where(['id' => $id])->first();

        $large_image_path = 'images/backend_images/products/large/' . $product->image;
        $medium_image_path = 'images/backend_images/products/medium/' . $product->image;
        $small_image_path = 'images/backend_images/products/small/' . $product->image;

        // File::delete($large_image_path, $medium_image_path, $small_image_path);
        if (file_exists($large_image_path)) {
            unlink($large_image_path);
        }
        if (file_exists($medium_image_path)) {
            unlink($medium_image_path);
        }
        if (file_exists($small_image_path)) {
            unlink($small_image_path);
        }

        // Soft Delete

        ProductsImage::where(['id' => $id])->delete();

        return redirect()->back()->with('flash_message_success', 'Image Removed Successfully');
    }

    // Add to Cart Function

    public function addToCart(Request $request)
    {
        $data = $request->all();

        if(empty($data['user_email']))
        {
            $data['user_email'] = '';
        }

        if(empty(Session::has('session_id')))
        {
            $session_id = str_random(40);
            Session::put('session_id', $session_id);
        }
        $session_id = Session::get('session_id');

        $sizeArray = explode('-', $data['size']);

        DB::table('cart')->insert(['product_id' => $data['product_id'], 'product_name' => $data['product_name'], 'product_code' => $data['product_code'], 'product_color' => $data['product_color'], 'price' => $data['price'], 'size' => $sizeArray[1], 'quantity' => $data['quantity'], 'user_email' => $data['user_email'], 'session_id' => $session_id, 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')]);

        return redirect()->back()->with('flash_message_success', 'Added to cart Successfully');
        // return redirect('cart')->with('flash_message_success', 'Added to cart Successfully');
    }

    // Display Cart Function

    public function cart(Request $request)
    {
        if(Session::has('session_id'))
        {
            $session_id = Session::get('session_id');
            $userCart = DB::table('cart')->where(['session_id' => $session_id])->get();

            foreach ($userCart as $key => $value)
            {
                $productDetails = Product::where('id', $value->product_id)->first();
                $userCart[$key]->image = $productDetails->image;
            }

            return view('products.cart')->with(compact('userCart'));
        }
        else
        {
            return view('products.cart');
        }
    }

    // Delete Cart Product
    public function deleteCartProduct($id = null)
    {
        DB::table('cart')->where('id', $id)->delete();

        return redirect('cart')->with('flash_message_success', 'Removed from cart Successfully');
    }
}
