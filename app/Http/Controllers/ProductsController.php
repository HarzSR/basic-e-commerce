<?php

namespace App\Http\Controllers;

use App\Banner;
use App\Category;
use App\Country;
use App\Coupon;
use App\DeliveryAddress;
use App\Exports\productsExport;
use App\Order;
use App\OrdersProduct;
use App\Product;
use App\ProductsAttribute;
use App\ProductsImage;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Image;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use Dompdf\Dompdf;
use Carbon\Carbon;

class ProductsController extends Controller
{
    // Add Product Function

    public function addProduct(Request $request)
    {
        if(Session::get('adminDetails')['products_edit_access'] == 0 && Session::get('adminDetails')['products_full_access'] == 0)
        {
            return redirect('/admin/dashboard')->with('flash_message_error', 'Sorry, you don\'t have access to this page. How did you manage to come here. Please let us know, so that we can fix this bug.');
        }

        if ($request->isMethod('POST'))
        {
            $data = $request->all();

            $validator = Validator::make($request->all(), [
                'category_id' => 'required|numeric',
                'product_name' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
                'product_code' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
                'product_color' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
                'description' => 'required',
                'care' => 'required',
                'price' => 'required|numeric',
                'image' => 'mimes:jpeg,png,jpg',
                'video' => 'mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime',
            ]);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput($request->input());
            }

            $product = new Product();
            $product->category_id = $data['category_id'];
            $product->product_name = $data['product_name'];
            $product->product_code = $data['product_code'];
            $product->product_color = $data['product_color'];
            if (!empty($data['description']))
            {
                $product->description = $data['description'];
            }
            else
            {
                $product->description = '';
            }
            if (!empty($data['care']))
            {
                $product->care = $data['care'];
            }
            else
            {
                $product->care = '';
            }
            if (!empty($data['sleeve']))
            {
                $product->sleeve = $data['sleeve'];
            }
            else
            {
                $product->sleeve = '';
            }
            if (!empty($data['pattern']))
            {
                $product->pattern = $data['pattern'];
            }
            else
            {
                $product->pattern = '';
            }
            $product->price = $data['price'];
            if (!empty($data['weight']))
            {
                $product->weight = $data['weight'];
            }
            else
            {
                $product->weight = 0;
            }
            if (empty($data['image']))
            {
                $product->image = '';
            }
            else
            {
                if ($request->hasFile('image'))
                {
                    $image_tmp = $request->file('image');

                    if ($image_tmp->isValid())
                    {
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
            if (empty($data['video']))
            {
                $product->video = '';
            }
            elseif($request->hasFile('video'))
            {
                $video_temp = $request->file('video');
                $video_name = $video_temp->getClientOriginalName();
                $video_extension = $video_temp->getClientOriginalExtension();
                $video_path = public_path() . '/videos/backend_videos/products';
                $video_temp->move($video_path, $video_name);
                $product->video = $video_name;
            }
            if (empty($data['feature_item']))
            {
                $product->feature_item = 0;
            }
            else
            {
                $product->feature_item = 1;
            }
            if (empty($data['status']))
            {
                $product->status = 0;
            }
            else
            {
                $product->status = 1;
            }

            $product->save();

            return redirect()->back()->with('flash_message_success', 'Product Added Successfully');
        }

        $categories = Category::where(['parent_id' => 0])->get();
        $categories_dropdown = "<option value='' selected disabled>Select</option>";

        foreach ($categories as $category)
        {
            $categories_dropdown .= "<option value='" . $category->id . "' >" . $category->name . "</option>";
            $sub_categories = Category::where(['parent_id' => $category->id])->get();

            foreach ($sub_categories as $sub_category)
            {
                $categories_dropdown .= "<option value='" . $sub_category->id . "'> &nbsp; --&nbsp;" . $sub_category->name . "</option>";
            }
        }

        $sleeveArray = DB::table('sleeve_info')->get();
        $patternArray = DB::table('pattern')->get();

        return view('admin.products.add_product')->with(compact('categories_dropdown', 'sleeveArray', 'patternArray'));
    }

    // View Product Function

    public function viewProducts()
    {
        if(Session::get('adminDetails')['products_view_access'] == 0 && Session::get('adminDetails')['products_edit_access'] == 0 && Session::get('adminDetails')['products_full_access'] == 0)
        {
            return redirect('/admin/dashboard')->with('flash_message_error', 'Sorry, you don\'t have access to this page. How did you manage to come here. Please let us know, so that we can fix this bug.');
        }

        $products = Product::orderby('id', 'DESC')->get();
        $productCount = Product::count();

        foreach ($products as $key => $val)
        {
            $category_name = Category::where(['id' => $val->category_id])->first();
            $products[$key]->category_name = $category_name->name;
        }

        return view('admin.products.view_products')->with(compact('products', 'productCount'));
    }

    // Edit Product Function

    public function editProduct(Request $request, $id = null)
    {
        if(Session::get('adminDetails')['products_edit_access'] == 0 && Session::get('adminDetails')['products_full_access'] == 0)
        {
            return redirect('/admin/dashboard')->with('flash_message_error', 'Sorry, you don\'t have access to this page. How did you manage to come here. Please let us know, so that we can fix this bug.');
        }

        if ($request->isMethod('POST'))
        {
            $data = $request->all();

            $validator = Validator::make($request->all(), [
                'category_id' => 'required|numeric',
                'product_name' => 'required|regex:/[a-zA-Z0-9\s]+/|max:255',
                'product_code' => 'required|regex:/[a-zA-Z0-9\s]+/|max:255',
                'product_color' => 'required|regex:/[a-zA-Z0-9\s]+/|max:255',
                'description' => 'required',
                'care' => 'required',
                'price' => 'required|numeric',
                'image' => 'image',
                'video' => 'mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime',
            ]);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput($request->input());
            }

            if ($request->hasFile('image'))
            {
                $image_tmp = $request->file('image');

                if ($image_tmp->isValid())
                {
                    $extension = $image_tmp->getClientOriginalExtension();
                    $fileName = time() . mt_rand() . '.' . $extension;

                    $large_image_path = 'images/backend_images/products/large/' . $fileName;
                    $medium_image_path = 'images/backend_images/products/medium/' . $fileName;
                    $small_image_path = 'images/backend_images/products/small/' . $fileName;

                    Image::make($image_tmp)->save($large_image_path);
                    Image::make($image_tmp)->resize(600, 600)->save($medium_image_path);
                    Image::make($image_tmp)->resize(300, 300)->save($small_image_path);
                }
            }
            else
            {
                if (empty($data['current_image']))
                {
                    $fileName = "";
                }
                else
                {
                    $fileName = $data['current_image'];
                }
            }

            if ($request->hasFile('video'))
            {
                $video_temp = $request->file('video');
                $video_name = $video_temp->getClientOriginalName();
                $video_extension = $video_temp->getClientOriginalExtension();
                $video_path = public_path() . '/videos/backend_videos/products';
                $video_temp->move($video_path, $video_name);
            }
            else
            {
                if (empty($data['current_video']))
                {
                    $video_name = "";
                }
                else
                {
                    $video_name = $data['current_video'];
                }
            }

            if (empty($data['description']))
            {
                $data['description'] = '';
            }

            if (empty($data['care']))
            {
                $data['care'] = '';
            }

            if (empty($data['sleeve']))
            {
                $data['sleeve'] = '';
            }

            if (empty($data['pattern']))
            {
                $data['pattern'] = '';
            }

            if (empty($data['weight']))
            {
                $data['weight'] = 0;
            }

            if (empty($data['status']))
            {
                $status = 0;
            }
            else
            {
                $status = 1;
            }

            if (empty($data['feature_item']))
            {
                $feature_item = 0;
            }
            else
            {
                $feature_item = 1;
            }

            Product::where(['id' => $id])->update(['category_id' => $data['category_id'], 'product_name' => $data['product_name'], 'product_code' => $data['product_code'], 'product_color' => $data['product_color'], 'description' => $data['description'], 'care' => $data['care'], 'sleeve' => $data['sleeve'], 'pattern' => $data['pattern'], 'price' => $data['price'], 'weight' => $data['weight'] ,'image' => $fileName, 'video' => $video_name, 'feature_item' => $feature_item , 'status' => $status]);

            return redirect()->back()->with('flash_message_success', 'Product Updated Successfully');
        }

        $productDetails = Product::where(['id' => $id])->first();

        $categories = Category::where(['parent_id' => 0])->get();
        $categories_dropdown = "<option value='' selected disabled>Select</option>";

        foreach ($categories as $category)
        {
            if ($category->id == $productDetails->category_id)
            {
                $selectVariable = "selected";
            }
            else
            {
                $selectVariable = "";
            }

            $categories_dropdown .= "<option value='" . $category->id . "' " . $selectVariable . ">" . $category->name . "</option>";
            $sub_categories = Category::where(['parent_id' => $category->id])->get();

            foreach ($sub_categories as $sub_category)
            {
                if ($sub_category->id == $productDetails->category_id)
                {
                    $selectVariable = "selected";
                }
                else
                {
                    $selectVariable = "";
                }

                $categories_dropdown .= "<option value='" . $sub_category->id . "' " . $selectVariable . "> &nbsp; --&nbsp; " . $sub_category->name . "</option>";
            }
        }

        $sleeveArray = DB::table('sleeve_info')->get();
        $patternArray = DB::table('pattern')->get();

        return view('admin.products.edit_product')->with(compact('productDetails', 'categories_dropdown', 'sleeveArray', 'patternArray'));
    }

    // Delete Product Function

    public function deleteProduct($id = null)
    {
        if(Session::get('adminDetails')['products_full_access'] == 0)
        {
            return redirect('/admin/dashboard')->with('flash_message_error', 'Sorry, you don\'t have access to this page. How did you manage to come here. Please let us know, so that we can fix this bug.');
        }

        $product = Product::where(['id' => $id])->first();

        $large_image_path = 'images/backend_images/products/large/' . $product->image;
        $medium_image_path = 'images/backend_images/products/medium/' . $product->image;
        $small_image_path = 'images/backend_images/products/small/' . $product->image;

        // File::delete($large_image_path, $medium_image_path, $small_image_path);
        if (file_exists($large_image_path) && !empty($product->image))
        {
            unlink($large_image_path);
        }
        if (file_exists($medium_image_path) && !empty($product->image))
        {
            unlink($medium_image_path);
        }
        if (file_exists($small_image_path) && !empty($product->image))
        {
            unlink($small_image_path);
        }

        $products = ProductsImage::where(['product_id' => $id])->first();
        $productsCount = ProductsImage::where(['product_id' => $id])->count();

        if($productsCount != 0)
        {
            foreach ($products as $product)
            {
                $large_image_path = 'images/backend_images/products/large/' . $product->image;
                $medium_image_path = 'images/backend_images/products/medium/' . $product->image;
                $small_image_path = 'images/backend_images/products/small/' . $product->image;

                // File::delete($large_image_path, $medium_image_path, $small_image_path);
                if (file_exists($large_image_path) && !empty($product->image))
                {
                    unlink($large_image_path);
                }
                if (file_exists($medium_image_path) && !empty($product->image))
                {
                    unlink($medium_image_path);
                }
                if (file_exists($small_image_path) && !empty($product->image))
                {
                    unlink($small_image_path);
                }
            }
        }

        $product = Product::where(['id' => $id])->first();

        $video_path = public_path() . '/videos/backend_videos/products/' . $product->video;

        // File::delete($video_path);
        if (file_exists($video_path) && !empty($product->video))
        {
            unlink($video_path);
        }

        ProductsAttribute::where(['product_id' => $id])->delete();

        Product::where(['id' => $id])->delete();

        return redirect()->back()->with('flash_message_success', 'Product removed successfully');
    }

    // Delete Product Image Function

    public function deleteProductImage($id = null)
    {
        if(Session::get('adminDetails')['products_full_access'] == 0)
        {
            return redirect('/admin/dashboard')->with('flash_message_error', 'Sorry, you don\'t have access to this page. How did you manage to come here. Please let us know, so that we can fix this bug.');
        }

        // Hard Delete

        $product = Product::where(['id' => $id])->first();

        $large_image_path = 'images/backend_images/products/large/' . $product->image;
        $medium_image_path = 'images/backend_images/products/medium/' . $product->image;
        $small_image_path = 'images/backend_images/products/small/' . $product->image;

        // File::delete($large_image_path, $medium_image_path, $small_image_path);
        if (file_exists($large_image_path) && !empty($product->image))
        {
            unlink($large_image_path);
        }
        if (file_exists($medium_image_path) && !empty($product->image))
        {
            unlink($medium_image_path);
        }
        if (file_exists($small_image_path) && !empty($product->image))
        {
            unlink($small_image_path);
        }

        // Soft Delete

        Product::where(['id' => $id])->update(['image' => '']);

        return redirect()->back()->with('flash_message_success', 'Product Image Removed Successfully');
    }

    // Delete Product Video Function

    public function deleteProductVideo($id = null)
    {
        if(Session::get('adminDetails')['products_full_access'] == 0)
        {
            return redirect('/admin/dashboard')->with('flash_message_error', 'Sorry, you don\'t have access to this page. How did you manage to come here. Please let us know, so that we can fix this bug.');
        }

        // Hard Delete

        $product = Product::where(['id' => $id])->first();

        $video_path = public_path() . '/videos/backend_videos/products/' . $product->video;

        // File::delete($video_path);
        if (file_exists($video_path) && !empty($product->video))
        {
            unlink($video_path);
        }

        // Soft Delete

        Product::where(['id' => $id])->update(['video' => '']);

        return redirect()->back()->with('flash_message_success', 'Product Video Removed Successfully');
    }

    // Add Product Attribute Function

    public function addAttributes(Request $request, $id = null)
    {
        if ($request->isMethod('POST'))
        {
            if(Session::get('adminDetails')['products_edit_access'] == 0 && Session::get('adminDetails')['products_full_access'] == 0)
            {
                return redirect('/admin/dashboard')->with('flash_message_error', 'Sorry, you don\'t have access to this page. How did you manage to come here. Please let us know, so that we can fix this bug.');
            }

            $data = $request->all();

            foreach ($data['sku'] as $key => $val)
            {
                if (!empty($val))
                {
                    $attributeSkuCheck = ProductsAttribute::where('sku', $val)->count();
                    if ($attributeSkuCheck > 0)
                    {
                        return redirect('/admin/add-attributes/' . $id)->with('flash_message_error', 'SKU \"' . $val . '\" already existing');
                    }
                    $attributeSizeCheck = ProductsAttribute::where(['product_id' => $id, 'size' => $data['size'][$key]])->count();
                    if ($attributeSizeCheck > 0)
                    {
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

        if(Session::get('adminDetails')['products_view_access'] == 0 && Session::get('adminDetails')['products_edit_access'] == 0 && Session::get('adminDetails')['products_full_access'] == 0)
        {
            $productDetails = "";
            $productDetailCount = "";
        }
        else
        {
            $productDetails = Product::with('attributes')->where(['id' => $id])->first();
            $productDetailCount = ProductsAttribute::where(['product_id' => $id])->count();
        }

        return view('admin.products.add_attributes')->with(compact('productDetails', 'productDetailCount'));
    }

    // Edit Attributes Function

    public function editAttributes(Request $request, $id = null)
    {
        if(Session::get('adminDetails')['products_edit_access'] == 0 && Session::get('adminDetails')['products_full_access'] == 0)
        {
            return redirect('/admin/dashboard')->with('flash_message_error', 'Sorry, you don\'t have access to this page. How did you manage to come here. Please let us know, so that we can fix this bug.');
        }

        if ($request->isMethod('POST'))
        {
            $data = $request->all();
            foreach ($data['idAttr'] as $key => $attr)
            {
                ProductsAttribute::where(['id' => $data['idAttr'][$key]])->update(['price' => $data['price'][$key], 'stock' => $data['stock'][$key]]);
            }

            return redirect()->back()->with('flash_message_success', 'Products Attribute Updated Successfully');
        }
    }

    // Add More Images Function

    public function addImages(Request $request, $id = null)
    {
        if ($request->isMethod('POST'))
        {
            if(Session::get('adminDetails')['products_edit_access'] == 0 && Session::get('adminDetails')['products_full_access'] == 0)
            {
                return redirect('/admin/dashboard')->with('flash_message_error', 'Sorry, you don\'t have access to this page. How did you manage to come here. Please let us know, so that we can fix this bug.');
            }

            $data = $request->all();

            if ($request->hasFile('image'))
            {
                $files = $request->file('image');
                foreach ($files as $file)
                {
                    if ($file->isValid())
                    {
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
        $productImageCount = ProductsImage::where(['product_id' => $id])->count();

        return view('admin.products.add_images')->with(compact('productDetails', 'productsImages', 'productImageCount'));
    }

    // Delete Product Attribute Function

    public function deleteAttribute($id = null)
    {
        if(Session::get('adminDetails')['products_full_access'] == 0)
        {
            return redirect('/admin/dashboard')->with('flash_message_error', 'Sorry, you don\'t have access to this page. How did you manage to come here. Please let us know, so that we can fix this bug.');
        }

        ProductsAttribute::where(['id' => $id])->delete();

        return redirect()->back()->with('flash_message_success', 'Product Attribute Deleted Successfully');
    }

    // Display Products Function

    public function products($url = null)
    {
        $categoryCount = Category::where(['url' => $url, 'status' => 1])->count();

        if ($categoryCount == 0)
        {
            abort(404);
        }

        $categories = Category::with('categories')->where(['parent_id' => 0])->get();
        $colors = Product::distinct()->where('status', 1)->get(['product_color']);
        $categoryDetails = Category::where(['url' => $url])->first();
        $sleeves = DB::table('sleeve_info')->get();
        $patterns = DB::table('pattern')->get();
        $sizes = ProductsAttribute::select('size')->groupBy('size')->get();
        $meta_title = $categoryDetails->meta_title;
        $meta_description = $categoryDetails->meta_description;
        $meta_keywords = $categoryDetails->meta_keywords;

        if ($categoryDetails->parent_id != 0)
        {
            $productsAll = Product::where(['products.category_id' => $categoryDetails->id])->where('products.status', 1);
            $mainCategory = Category::where('id', $categoryDetails->parent_id)->first();
            $breadcrumb = "<a href='/'> Home</a> | <a href='" . $mainCategory->url . "'>" . $mainCategory->name . "</a> | <a href='" . $categoryDetails->url . "'>" . $categoryDetails->name . "</a>";
        }
        else
        {
            $subCategories = Category::where(['parent_id' => $categoryDetails->id])->get();
            foreach ($subCategories as $subCategory)
            {
                $categoryIds[] = $subCategory->id;
            }
            array_push($categoryIds, $categoryDetails->id);
            $productsAll = Product::whereIn('products.category_id', $categoryIds)->where('products.status', 1);
            $breadcrumb = "<a href='/'> Home</a> | <a href='" . $categoryDetails->url . "'>" . $categoryDetails->name . "</a>";
        }

        if(!empty($_GET['color']))
        {
            $colorArray = explode('-', $_GET['color']);
            $productsAll = $productsAll->whereIn('products.product_color', $colorArray);
        }

        if(!empty($_GET['sleeve']))
        {
            $sleeveArray = explode('-', $_GET['sleeve']);
            $productsAll = $productsAll->whereIn('products.sleeve', $sleeveArray);
        }

        if(!empty($_GET['pattern']))
        {
            $patternArray = explode('-', $_GET['pattern']);
            $productsAll = $productsAll->whereIn('pattern', $patternArray);
        }

        if(!empty($_GET['size']))
        {
            $sizeArray = explode('-', $_GET['size']);
            $productsAll = $productsAll->join('products_attributes', 'products_attributes.product_id', '=', 'products.id')->select('products.*', 'products_attributes.product_id', 'products_attributes.size')->groupBy('products_attributes.product_id')->whereIn('products_attributes.size',$sizeArray);
        }

        $productsAll = $productsAll->paginate(3);

        $banners = Banner::where('status', 1)->get();

        return view('products.listing')->with(compact('categoryDetails', 'productsAll', 'categories', 'meta_title', 'meta_description', 'meta_keywords', 'banners', 'url', 'colors', 'sleeves', 'patterns', 'sizes', 'breadcrumb'));
    }

    // Display Individual Product Function

    public function product($id = null)
    {
        $productsCount = Product::where(['id' => $id, 'status' => 1])->count();

        if ($productsCount == 0)
        {
            abort(404);
        }

        $productDetails = Product::with('attributes')->where(['id' => $id])->first();
        // $productAltImages = ProductsImage::where('parent_id', $id)->get();
        $meta_title = $productDetails->product_name;
        $meta_description = $productDetails->description;
        $meta_keywords = $productDetails->product_name;

        $categories = Category::with('categories')->where(['parent_id' => 0])->get();
        $categoryDetails = Category::where('id', $productDetails->category_id)->first();

        if($categoryDetails->parent_id != 0)
        {
            $mainCategory = Category::where('id', $categoryDetails->parent_id)->first();
            $breadcrumb = "<a href='/'> Home</a> | <a href='/products/" . $mainCategory->url . "'>" . $mainCategory->name . "</a> | <a href='/products/" . $categoryDetails->url . "'>" . $categoryDetails->name . "</a> | " . $productDetails->product_name;
        }
        else
        {
            $breadcrumb = "<a href='/'> Home</a> | <a href='" . $categoryDetails->url . "'>" . $categoryDetails->name . "</a>";
        }

        $colors = Product::distinct()->where('status', 1)->get(['product_color']);
        $sleeves = DB::table('sleeve_info')->get();
        $patterns = DB::table('pattern')->get();

        $productAdditionalImages = ProductsImage::where('product_id', $id)->get();

        $total_stock = ProductsAttribute::where('product_id', $id)->sum('stock');

        $relatedProducts = Product::where('id', '!=', $id)->where(['category_id' => $productDetails->category_id])->get();

        return view('products.detail')->with(compact('productDetails', 'categories', 'productAdditionalImages', 'total_stock', 'relatedProducts', 'meta_title', 'meta_description', 'meta_keywords', 'id', 'colors', 'sleeves', 'patterns', 'breadcrumb'));
    }

    // Get Product Price upon Attribute Change Function

    public function getProductPrice(Request $request)
    {
        $data = $request->all();

        $sizeArray = explode('-', $data['idSize']);
        $productAttribute = ProductsAttribute::where(['product_id' => $sizeArray[0], 'size' => $sizeArray[1]])->first();
        $getCurrencyRates = Product::getCurrencyRates($productAttribute->price);

        echo $productAttribute->price . '-' . $getCurrencyRates['USD_Rate'] . '-' . $getCurrencyRates['GBP_Rate'] . '-' . $getCurrencyRates['EUR_Rate'] . '-' . $getCurrencyRates['NZD_Rate'] . '#' . $productAttribute->stock;
    }

    // Delete Additional Image Function

    public function deleteAdditionalImage($id = null)
    {
        if(Session::get('adminDetails')['products_full_access'] == 0)
        {
            return redirect('/admin/dashboard')->with('flash_message_error', 'Sorry, you don\'t have access to this page. How did you manage to come here. Please let us know, so that we can fix this bug.');
        }

        // Hard Delete

        $product = ProductsImage::where(['id' => $id])->first();

        $large_image_path = 'images/backend_images/products/large/' . $product->image;
        $medium_image_path = 'images/backend_images/products/medium/' . $product->image;
        $small_image_path = 'images/backend_images/products/small/' . $product->image;

        // File::delete($large_image_path, $medium_image_path, $small_image_path);
        if (file_exists($large_image_path) && !empty($product->image))
        {
            unlink($large_image_path);
        }
        if (file_exists($medium_image_path) && !empty($product->image))
        {
            unlink($medium_image_path);
        }
        if (file_exists($small_image_path) && !empty($product->image))
        {
            unlink($small_image_path);
        }

        // Soft Delete

        ProductsImage::where(['id' => $id])->delete();

        return redirect()->back()->with('flash_message_success', 'Image Removed Successfully');
    }

    // Add to Cart Function

    public function addToCart(Request $request)
    {
        Session::forget('couponAmount');
        Session::forget('couponCode');

        $data = $request->all();

        if(!empty($data['wishListButton']) && ($data['wishListButton'] == "Add Wish List" || $data['wishListButton'] == "Move Wish List"))
        {
            if(!Auth::check())
            {
                return redirect()->back()->with('flash_message_error', 'Please log in to add to Wish List');
            }

            if(empty($data['size']))
            {
                return redirect()->back()->with('flash_message_error', 'Please select Size to add to Wish List');
            }

            $sizeArray = explode('-', $data['size']);

            $productPrice = ProductsAttribute::where(['product_id' => $data['product_id'], 'size' => $sizeArray[1]])->first();

            $user_email = Auth::user()->email;
            $created_at = Carbon::now();
            $updated_at = Carbon::now();

            $wishListCount = DB::table('wish_list')->where(['user_email' => $user_email, 'product_id' => $data['product_id'], 'product_name' => $data['product_name'], 'product_code' => $data['product_code'],'product_color' => $data['product_color'], 'size' => $sizeArray[1]])->count();

            if($wishListCount >= 1)
            {
                return redirect()->back()->with('flash_message_error','Product already in Wish List');
            }
            else
            {
                DB::table('wish_list')->insert(['product_id' => $data['product_id'], 'product_name' => $data['product_name'], 'product_code' => $data['product_code'], 'product_color' => $data['product_color'], 'size' => $sizeArray[1], 'price' => $productPrice->price, 'quantity' => $data['quantity'], 'user_email' => $user_email, 'created_at' => $created_at, 'updated_at' => $updated_at]);

                if(!empty($data['wishListButton']) && $data['wishListButton'] == "Move Wish List")
                {
                    DB::table('cart')->where(['product_id' => $data['product_id'], 'user_email' => $user_email])->delete();

                    return redirect()->back()->with('flash_message_success', 'Product moved to Wishlist Successfully');
                }

                return redirect()->back()->with('flash_message_success', 'Product added to Wishlist Successfully');
            }
        }
        else
        {
            if(empty(Auth::User()->email))
            {
                $data['user_email'] = '';
            }
            else
            {
                $data['user_email'] = Auth::User()->email;
            }

            if(empty(Session::has('session_id')))
            {
                $session_id = str_random(40);
                Session::put('session_id', $session_id);
            }
            $session_id = Session::get('session_id');

            $sizeArray = explode('-', $data['size']);

            $countProducts = DB::table('cart')->where(['product_id' => $data['product_id'], 'product_color' => $data['product_color'], 'size' => $sizeArray[1], 'session_id' => $session_id])->count();

            if($countProducts > 0)
            {
                return redirect()->back()->with('flash_message_error', 'Product already exist');

                // DB::table('cart')->where( 'session_id', $session_id)->increment('quantity', $data['quantity']);

                // return redirect()->back()->with('flash_message_success', 'Product Already Exist. Cart Updated Successfully');
            }
            else
            {
                $getSku = ProductsAttribute::select('sku')->where(['product_id' => $data['product_id'], 'size' => $sizeArray[1]])->first();

                $getStockCount = ProductsAttribute::select('stock')->where(['product_id' => $data['product_id'], 'size' => $sizeArray[1]])->first();

                if($data['quantity'] > $getStockCount->stock)
                {
                    return redirect()->back()->with('flash_message_error', 'Quantity out of Stock, Please choose a lower quantity. Available quantity - ' . $getStockCount->stock);
                }

                DB::table('cart')->insert(['product_id' => $data['product_id'], 'product_name' => $data['product_name'], 'product_code' => $getSku->sku, 'product_color' => $data['product_color'], 'price' => $data['price'], 'size' => $sizeArray[1], 'quantity' => $data['quantity'], 'user_email' => $data['user_email'], 'session_id' => $session_id, 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')]);

                if(!empty($data['cartButton']) && $data['cartButton'] == "Move To Cart")
                {
                    DB::table('wish_list')->where(['product_id' => $data['product_id'], 'user_email' => $data['user_email']])->delete();

                    return redirect()->back()->with('flash_message_success', 'Moved to cart Successfully');
                }

                return redirect()->back()->with('flash_message_success', 'Added to cart Successfully');
            }

            // return redirect('cart')->with('flash_message_success', 'Added to cart Successfully');
        }
    }

    // Display Cart Function

    public function cart(Request $request)
    {
        if(!empty(Auth::User()->email))
        {
            $user_email = Auth::user()->email;
            $userCartCount = DB::table('cart')->where(['user_email' => $user_email])->count();
        }
        else
        {
            $userCartCount = 0;
        }

        if(Session::has('session_id'))
        {
            /*
             *
             * // Get all cart items instead of just current session
             *
            if(Auth::check())
            {
                $user_email = Auth::User()->email;
                $userCart = DB::table('cart')->where(['user_email' => $user_email])->get();
            }
            else
            {
                $session_id = Session::get('session_id');
                $userCart = DB::table('cart')->where(['session_id' => $session_id])->get();
            }
            */
            $session_id = Session::get('session_id');
            $userCart = DB::table('cart')->where(['session_id' => $session_id])->get();

            foreach ($userCart as $key => $value)
            {
                $productDetails = Product::where('id', $value->product_id)->first();
                $userCart[$key]->image = $productDetails->image;
            }

            $meta_title = "Cart";
            $meta_description = "Cart for Shopping";
            $meta_keywords = "cart,shopping";

            return view('products.cart')->with(compact('userCart', 'meta_title', 'meta_description', 'meta_keywords'));
        }
        else if($userCartCount > 0)
        {
            $userCart = DB::table('cart')->where(['user_email' => $user_email])->get();

            foreach ($userCart as $key => $value)
            {
                $productDetails = Product::where('id', $value->product_id)->first();
                $userCart[$key]->image = $productDetails->image;
            }

            $meta_title = "Cart";
            $meta_description = "Cart for Shopping";
            $meta_keywords = "cart,shopping";

            return view('products.cart')->with(compact('userCart', 'meta_title', 'meta_description', 'meta_keywords'));

        }
        else
        {
            $meta_title = "Cart";
            $meta_description = "Cart for Shopping";
            $meta_keywords = "cart,shopping";

            return view('products.cart')->with(compact('meta_title', 'meta_description', 'meta_keywords'));
        }
    }

    // Delete Cart Product Function

    public function deleteCartProduct($id = null)
    {
        Session::forget('couponAmount');
        Session::forget('couponCode');

        DB::table('cart')->where('id', $id)->delete();

        return redirect('cart')->with('flash_message_success', 'Removed from cart Successfully');
    }

    // Update Cart Quantities Function

    public function updateCartQuantity($id = null, $quantity = null)
    {
        Session::forget('couponAmount');
        Session::forget('couponCode');

        $getCartDetails = DB::table('cart')->where('id', $id)->first();
        $getAttributeStock = ProductsAttribute::where('sku', $getCartDetails->product_code)->first();

        if($getCartDetails->quantity + $quantity <= $getAttributeStock->stock)
        {
            DB::table('cart')->where('id', $id)->increment('quantity', $quantity);

            return redirect('cart')->with('flash_message_success', 'Product updated Successfully');
        }
        else
        {
            return redirect('cart')->with('flash_message_error', 'Requested Quantity Unavailable. This it the last available quantity - ' . $getAttributeStock->stock);
        }
    }

    // Apply Coupon Function

    public function applyCoupon(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $data = $request->all();

            $couponCount = Coupon::where('coupon_code', $data['coupon_code'])->count();

            if($couponCount == 0)
            {
                return redirect()->back()->with('flash_message_error', 'Invalid Coupon Code');
            }
            else
            {
                $couponDetails = Coupon::where('coupon_code', $data['coupon_code'])->first();

                if($couponDetails->status == 0)
                {
                    return redirect()->back()->with('flash_message_error', 'Coupon Code is In-Active');
                }
                if($couponDetails->expiry_date < date('Y-m-d'))
                {
                    return redirect()->back()->with('flash_message_error', 'Coupon Code Expired');
                }

                Session::forget('couponAmount');
                Session::forget('couponCode');

                $userCart = DB::table('cart')->where(['session_id' => Session::get('session_id')])->get();
                $total_amount = 0;

                foreach ($userCart as $item)
                {
                    $total_amount = $total_amount + ($item->price * $item->quantity);
                }

                if($couponDetails->amount_type == 'Percentage')
                {
                    $couponAmount = $total_amount * $couponDetails->amount / 100;
                }
                else if($couponDetails->amount_type == 'Fixed')
                {
                    $couponAmount = $couponDetails->amount;
                }

                Session::put('couponAmount', $couponAmount);
                Session::put('couponCode', $data['coupon_code']);

                return redirect()->back()->with('flash_message_success', 'Coupon added Successfully');
            }
        }
    }

    // Remove Coupon Function

    public function removeCoupon()
    {
        Session::forget('couponAmount');
        Session::forget('couponCode');

        return redirect()->back()->with('flash_message_success', 'Coupon removed Successfully');
    }

    // Checkout Function

    public function checkout(Request $request)
    {
        $user_id = Auth::User()->id;
        $user_email = Auth::User()->email;
        $userDetails = User::find($user_id);
        $countries = Country::get();

        $shippingCount = DeliveryAddress::where('user_id', $user_id)->count();

        if($shippingCount > 0)
        {
            $shippingDetails = DeliveryAddress::where('user_id', $user_id)->first();
        }
        else
        {
            $shippingDetails = '';
        }

        $session_id = Session::get('session_id');

        DB::table('cart')->where(['session_id' => $session_id])->update(['user_email' => $user_email]);

        if($request->isMethod('POST'))
        {
            $data = $request->all();

            if(empty($data['billing_name']) || empty($data['billing_address']) || empty($data['billing_city']) || empty($data['billing_state']) || empty($data['billing_country']) || empty($data['billing_pincode']) || empty($data['billing_mobile']) || empty($data['billing_mobile']) || empty($data['shipping_address']) || empty($data['shipping_city']) || empty($data['shipping_state']) || empty($data['shipping_country']) || empty($data['shipping_pincode']) || empty($data['shipping_mobile']))
            {
                return redirect()->back()->with('flash_message_error', 'Please fill in all details');
            }

            User::where('id', $user_id)->update(['name' => $data['billing_name'], 'address' => $data['billing_address'], 'city' => $data['billing_city'], 'state' => $data['billing_state'], 'country' => $data['billing_country'], 'pincode' => $data['billing_pincode'], 'mobile' => $data['billing_mobile']]);

            if($shippingCount > 0)
            {
                DeliveryAddress::where('user_id', $user_id)->update(['name' => $data['shipping_name'], 'address' => $data['shipping_address'], 'city' => $data['shipping_city'], 'state' => $data['shipping_state'], 'country' => $data['shipping_country'], 'pincode' => $data['shipping_pincode'], 'mobile' => $data['shipping_mobile']]);
            }
            else
            {
                $shipping = new DeliveryAddress;
                $shipping->user_id = $user_id;
                $shipping->user_email = $user_email;
                $shipping->name = $data['shipping_name'];
                $shipping->address = $data['shipping_address'];
                $shipping->city = $data['shipping_city'];
                $shipping->state = $data['shipping_state'];
                $shipping->country = $data['shipping_country'];
                $shipping->pincode = $data['shipping_pincode'];
                $shipping->mobile = $data['shipping_mobile'];

                $shipping->save();
            }

            $shippingDetails = DeliveryAddress::where(['user_email' => $user_email])->first();
            $pincodeCount = DB::table('pincodes')->where('pincode', $shippingDetails->pincode)->count();

            if($pincodeCount == 0)
            {
                return redirect()->back()->with('flash_message_error', 'Your Location is currently unserviceable. Please choose a friends place or a different address');
            }

            return redirect()->action('ProductsController@orderReview');
        }

        $meta_title = "Check-out Process";
        $meta_description = "Checking out Shopping Cart";
        $meta_keywords = "check-out,shopping";

        return view('products.checkout')->with(compact('userDetails', 'countries', 'shippingDetails', 'meta_title', 'meta_description', 'meta_keywords'));
    }

    // Order Review Function

    public function orderReview()
    {
        $user_id = Auth::User()->id;
        $user_email = Auth::User()->email;
        $userDetails = User::find($user_id);
        $session_id = Session::get('session_id');
        $shippingDetails = DeliveryAddress::where('user_id', $user_id)->first();
        $userCart = DB::table('cart')->where(['user_email' => $user_email, 'session_id' => $session_id])->get();
        $total_weight = 0;

        foreach ($userCart as $key => $product)
        {
            $productDetails = Product::where('id', $product->product_id)->first();
            $userCart[$key]->image = $productDetails->image;
            $total_weight = $total_weight + $productDetails->weight;
        }

        $codPincodeCount = DB::table('cod_pincodes')->where('pincode', $shippingDetails->pincode)->count();
        $prepaidPincodeCount = DB::table('prepaid_pincodes')->where('pincode', $shippingDetails->pincode)->count();
        $shippingCharges = Product::getShippingCharges($total_weight, $shippingDetails->country);
        Session::put('ShippingCharges', $shippingCharges);

        $meta_title = "Order Review";
        $meta_description = "Check before you place your Order";
        $meta_keywords = "place-order,shopping";

        return view('products.order_review')->with(compact('userDetails', 'shippingDetails', 'userCart', 'meta_title', 'meta_description', 'meta_keywords', 'codPincodeCount', 'prepaidPincodeCount', 'shippingCharges'));
    }

    // Place Order Function

    public function placeOrder(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $data = $request->all();

            $user_id = Auth::User()->id;
            $user_email = Auth::User()->email;
            $session_id = Session::get('session_id');
            $userCart = DB::table('cart')->where('user_email', $user_email)->get();
            $shippingDetails = DeliveryAddress::where(['user_email' => $user_email])->first();
            $pincodeCount = DB::table('pincodes')->where('pincode', $shippingDetails->pincode)->count();

            foreach ($userCart as $cart)
            {
                $getAttributeCount = Product::getAttributeCount($cart->product_id, $cart->size);

                if($getAttributeCount == 0)
                {
                    Product::deleteCartProduct($cart->product_id ,$user_email);

                    return redirect('/cart')->with('flash_message_error', 'Cart item removed, as the seller removed the item. Please try again.');
                }

                $product_stock = Product::getProductStock($cart->product_id, $cart->size);

                if($product_stock == 0)
                {
                    Product::deleteCartProduct($cart->product_id ,$user_email);

                    return redirect('/cart')->with('flash_message_error', 'Cart item removed, as one of the item is sold out. Please try again.');
                }
                if($cart->quantity > $product_stock)
                {
                    return redirect('/cart')->with('flash_message_error', 'Unfortunately, we dont have so much in stock. Please update cart values.');
                }

                $product_status = Product::getProductStatus($cart->product_id);

                if($product_status == 0)
                {
                    Product::deleteCartProduct($cart->product_id);

                    return redirect('/cart')->with('flash_message_error', 'Cart item removed as seller disabled it. Please try again.');
                }

                $getCategoryId = Product::select('category_id')->where('id', $cart->product_id)->first();
                $categoryStatus = Product::getCategoryStatus($getCategoryId->category_id);

                if($categoryStatus == 0)
                {
                    Product::deleteCartProduct($cart->product_id ,$user_email);

                    return redirect('/cart')->with('flash_message_error', 'Cart item removed, as one of the item is disabled by Seller. Please try again.');
                }
            }

            if($pincodeCount == 0)
            {
                return redirect()->back()->with('flash_message_error', 'Your Location is currently unserviceable. Please choose a friends place or a different address');
            }

            if(empty(Session::has('couponCode')) || empty(Session::has('couponAmount')))
            {
                $couponCode = '0';
                $couponAmount = '0';
            }
            else
            {
                $couponCode = Session::get('couponCode');
                $couponAmount = Session::get('couponAmount');
            }

            // Removed as Advance Shipping Charges were implemented
            // $shippingCharges = Product::getShippingCharges($shippingDetails->country);

            $grand_total = Product::getGrandTotal();

            $order = new Order;
            $order->user_id = $user_id;
            $order->user_email = $user_email;
            $order->name = $shippingDetails->name;
            $order->address = $shippingDetails->address;
            $order->city = $shippingDetails->city;
            $order->state = $shippingDetails->state;
            $order->pincode = $shippingDetails->pincode;
            $order->country = $shippingDetails->country;
            $order->mobile = $shippingDetails->mobile;

            if(Session::has('ShippingCharges'))
            {
                $order->shipping_charges = Session::get('ShippingCharges');
            }
            else
            {
                $order->shipping_charges = 0;
            }
            $order->coupon_code = $couponCode;
            $order->coupon_amount = $couponAmount;
            $order->order_status = "New";
            $order->payment_method = $data['payment_method'];
            $order->grand_total = $grand_total;

            $order->save();

            $order_id = DB::getPdo()->lastInsertId();

            $cartProducts = DB::table('cart')->where(['user_email' => $user_email, 'session_id' => $session_id])->get();

            foreach ($cartProducts as $cartProduct)
            {
                $cartPro = New OrdersProduct;
                $cartPro->order_id = $order_id;
                $cartPro->user_id = $user_id;
                $cartPro->product_id = $cartProduct->product_id;
                $cartPro->product_code = $cartProduct->product_code;
                $cartPro->product_name = $cartProduct->product_name;
                $cartPro->product_color = $cartProduct->product_color;
                $cartPro->product_size = $cartProduct->size;
                $product_price = Product::getProductPrice($cartProduct->product_id. $cartProduct->size);
                $cartPro->product_price = $product_price;
                $cartPro->product_qty = $cartProduct->quantity;

                $cartPro->save();

                $getProductStock = ProductsAttribute::where('sku', $cartProduct->product_code)->first();

                if($getProductStock->stock - $cartProduct->quantity < 0)
                {
                    $newStock = 0;
                }
                else
                {
                    $newStock = $getProductStock->stock - $cartProduct->quantity;
                }

                ProductsAttribute::where('sku', $cartProduct->product_code)->update(['stock' => $newStock]);
            }

            Session::put('order_id', $order_id);
            Session::put('grand_total', $grand_total);

            if($data['payment_method'] == "COD")
            {
                $productDetails = Order::with('orders')->where('id', $order_id)->first();
                $userDetails = User::where('id', $user_id)->first();

                // Email upon Successful Order

                /*
                $email = $user_email;
                $messageData = [
                    'email' => $user_email,
                    'name' => $shippingDetails->name,
                    'order_id' => $order_id,
                    'productDetails' => $productDetails,
                    'userDetails' => $userDetails
                ];
                Mail::send('emails.order', $messageData, function ($message) use($email) {
                    $message->to($email)->subject('Order Placed Successfully');
                });
                */

                $meta_title = "COD Placed";
                $meta_description = "COD for Order Placed";
                $meta_keywords = "cod,shopping";

                Session::forget('ShippingCharges');

                return redirect(('/thanks'))->with(compact( 'meta_title', 'meta_description', 'meta_keywords'));
            }
            else if($data['payment_method'] == "Paypal")
            {
                $meta_title = "Paypal Placed";
                $meta_description = "Paypal for Order Placed";
                $meta_keywords = "paypal,shopping";

                return redirect(('/paypal'))->with(compact( 'meta_title', 'meta_description', 'meta_keywords'));
            }
        }
    }

    // Order Confirmation - COD Function

    public function thanks(Request $request)
    {
        $user_email = Auth::User()->email;

        DB::table('cart')->where('user_email', $user_email)->delete();

        return view('orders.thanks');
    }

    // User Order Function

    public function userOrders(Request $request)
    {
        $user_id = Auth::User()->id;
        $orders = Order::with('orders')->where('user_id', $user_id)->get();

        return view('orders.user_orders')->with(compact('orders'));
    }

    // User Order Details Function

    public function userOrderDetails($id = null)
    {
        $orderDetails = Order::with('orders')->where('id', $id)->first();

        return view('orders.user_order_details')->with(compact('orderDetails'));
    }

    // Paypal Function

    public function paypal(Request $request)
    {
        $user_email = Auth::User()->email;

        DB::table('cart')->where('user_email', $user_email)->delete();

        return view('orders.paypal');
    }

    // Thanks Paypal - Paypal Confirmation Function

    public function thanksPaypal()
    {
        return view('orders.thanks_paypal');
    }

    // Cancel Paypal - Paypal Cancellation Function

    public function cancelPaypal()
    {
        return view('orders.cancel_paypal');
    }

    // Admin View Orders Function

    public function viewOrders()
    {
        if(Session::get('adminDetails')['orders_view_access'] == 0 && Session::get('adminDetails')['orders_edit_access'] == 0 && Session::get('adminDetails')['orders_full_access'] == 0)
        {
            return redirect('/admin/dashboard')->with('flash_message_error', 'Sorry, you don\'t have access to this page. How did you manage to come here. Please let us know, so that we can fix this bug.');
        }

        $orders = Order::with('orders')->orderBy('id','DESC')->get();
        $orderCount = Order::count();

        return view('admin.orders.view_orders')->with(compact('orders', 'orderCount'));
    }

    // View Specific Order Details

    public function viewOrdersDetails($order_id = null)
    {
        if(Session::get('adminDetails')['orders_view_access'] == 0 && Session::get('adminDetails')['orders_edit_access'] == 0 && Session::get('adminDetails')['orders_full_access'] == 0)
        {
            return redirect('/admin/dashboard')->with('flash_message_error', 'Sorry, you don\'t have access to this page. How did you manage to come here. Please let us know, so that we can fix this bug.');
        }

        $orderDetails = Order::with('orders')->where('id', $order_id)->first();

        $user_id = $orderDetails->user_id;
        $userDetails = User::where('id', $user_id)->first();

        return view('admin.orders.order_details')->with(compact('orderDetails', 'userDetails'));
    }

    // View Specific Order Invoice Details

    public function viewOrdersInvoice($order_id = null)
    {
        if(Session::get('adminDetails')['orders_view_access'] == 0 && Session::get('adminDetails')['orders_edit_access'] == 0 && Session::get('adminDetails')['orders_full_access'] == 0)
        {
            return redirect('/admin/dashboard')->with('flash_message_error', 'Sorry, you don\'t have access to this page. How did you manage to come here. Please let us know, so that we can fix this bug.');
        }

        $orderDetails = Order::with('orders')->where('id', $order_id)->first();

        $user_id = $orderDetails->user_id;
        $userDetails = User::where('id', $user_id)->first();

        return view('admin.orders.order_invoice')->with(compact('orderDetails', 'userDetails'));
    }

    // View Specific Order PDF Invoice Details

    public function viewPDFInvoice($order_id = null)
    {
        if(Session::get('adminDetails')['orders_view_access'] == 0 && Session::get('adminDetails')['orders_edit_access'] == 0 && Session::get('adminDetails')['orders_full_access'] == 0)
        {
            return redirect('/admin/dashboard')->with('flash_message_error', 'Sorry, you don\'t have access to this page. How did you manage to come here. Please let us know, so that we can fix this bug.');
        }

        $orderDetails = Order::with('orders')->where('id', $order_id)->first();

        $user_id = $orderDetails->user_id;
        $userDetails = User::where('id', $user_id)->first();

        $output = '<!DOCTYPE html>
                   <html lang="en">
                   <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                    <head>
                        <meta charset="utf-8">
                        <title>Example 1</title>
                        <style>
                            .clearfix:after
                            {
                                content: "";
                                display: table;
                                clear: both;
                            }
                            a
                            {
                                color: #5D6975;
                                text-decoration: underline;
                            }
                            body
                            {
                                position: relative;
                                width: 21cm;
                                height: 29.7cm;
                                margin: 0 auto;
                                color: #001028;
                                background: #FFFFFF;
                                font-family: Arial, sans-serif;
                                font-size: 12px;
                                font-family: Arial;
                            }
                            header
                            {
                                padding: 10px 0;
                                margin-bottom: 30px;
                            }
                            #logo
                            {
                                text-align: center;
                                margin-bottom: 10px;
                            }
                            #logo img
                            {
                                width: 90px;
                            }
                            h1
                            {
                                border-top: 1px solid  #5D6975;
                                border-bottom: 1px solid  #5D6975;
                                color: #5D6975;
                                font-size: 2.4em;
                                line-height: 1.4em;
                                font-weight: normal;
                                text-align: center;
                                margin: 0 0 20px 0;
                                background: url(dimension.png);
                            }
                            #project
                            {
                                float: left;
                            }
                            #project span
                            {
                                color: #5D6975;
                                text-align: right;
                                width: 52px;
                                margin-right: 10px;
                                display: inline-block;
                                font-size: 0.8em;
                            }
                            #company
                            {
                                float: right;
                                text-align: right;
                            }
                            #project div, #company div
                            {
                                white-space: nowrap;
                            }
                            table
                            {
                                width: 100%;
                                border-collapse: collapse;
                                border-spacing: 0;
                                margin-bottom: 20px;
                            }
                            table tr:nth-child(2n-1) td
                            {
                                background: #F5F5F5;
                            }
                            table th, table td
                            {
                                text-align: center;
                            }
                            table th
                            {
                                padding: 5px 20px;
                                color: #5D6975;
                                border-bottom: 1px solid #C1CED9;
                                white-space: nowrap;
                                font-weight: normal;
                            }
                            table .service, table .desc
                            {
                                text-align: left;
                            }
                            table td
                            {
                                padding: 20px;
                                text-align: right;
                            }
                            table td.service, table td.desc
                            {
                                vertical-align: top;
                            }
                            table td.unit, table td.qty, table td.total
                            {
                                font-size: 1.2em;
                            }
                            table td.grand
                            {
                                border-top: 1px solid #5D6975;;
                            }
                            #notices .notice
                            {
                                color: #5D6975;
                                font-size: 1.2em;
                            }
                            footer
                            {
                                color: #5D6975;
                                width: 100%;
                                height: 30px;
                                position: absolute;
                                bottom: 0;
                                border-top: 1px solid #C1CED9;
                                padding: 8px 0;
                                text-align: center;
                            }
                            @page
                            {
                                margin-top: 5px;
                                margin-bottom: 5px;
                                margin-left: 80px;
                                margin-right: 80px;
                            }
                            body
                            {
                                margin-top: 5px;
                                margin-bottom: 5px;
                                margin-left: 80px;
                                margin-right: 80px;
                            }
                        </style>
                    </head>
                    <body>
                        <header class="clearfix">
                          <div id="logo">
                            <img src="images/backend_images/logo.png">
                          </div>
                          <h1>INVOICE ' . $orderDetails->id . ' </h1>
                          <div id="project" class="clearfix">
                            <div><span>Order ID</span> ' . $orderDetails->id . ' </div>
                            <div><span>Order Date</span>  ' . date('d-M-Y h:i A', strtotime($orderDetails->created_at)) . ' </div>
                            <div><span>Order Amount</span> ' . $orderDetails->grand_total . ' </div>
                            <div><span>Order Status</span> ' . $orderDetails->order_status . ' </div>
                            <div><span>Payment Method</span> ' . $orderDetails->payment_method . ' </div>
                          </div>
                          <div id="project" style="float:right;">
                            <div><strong>Shipping Address</strong></div>
                            <div> ' . $orderDetails->name . ' </div>
                            <div> ' . $orderDetails->address . ' </div>
                            <div> ' . $orderDetails->city . ', ' . $orderDetails->state . ' </div>
                            <div> ' . $orderDetails->pincode . ' </div>
                            <div> ' . $orderDetails->country . ' </div>
                            <div> ' . $orderDetails->mobile . ' </div>
                          </div>
                        </header>
                        <main>
                          <table>
                            <thead>
                                <tr>
                                    <td style="width:18%"><strong>Product Code</strong></td>
                                    <td style="width:18%" class="text-center"><strong>Size</strong></td>
                                    <td style="width:18%" class="text-center"><strong>Color</strong></td>
                                    <td style="width:18%" class="text-center"><strong>Price</strong></td>
                                    <td style="width:18%" class="text-center"><strong>Qty</strong></td>
                                    <td style="width:18%" class="text-right"><strong>Totals</strong></td>
                                </tr>
                            </thead>
                            <tbody>';
                    $Subtotal = 0;
                    foreach($orderDetails->orders as $pro)
                    {
                        $output .= '<tr>
                                        <td class="text-left"> ' . $pro->product_code . ' </td>
                                        <td class="text-center"> ' . $pro->product_size . ' </td>
                                        <td class="text-center"> ' . $pro->product_color . ' </td>
                                        <td class="text-center"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> ' . $pro->product_price . ' </td>
                                        <td class="text-center"> ' . $pro->product_qty . ' </td>
                                        <td class="text-right"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> ' . $pro->product_price * $pro->product_qty . ' </td>
                                    </tr>';
                        $Subtotal = $Subtotal + ($pro->product_price * $pro->product_qty);
                    }
                    $output .= '<tr>
                                    <td colspan="5">SUBTOTAL</td>
                                    <td class="total"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> ' . $Subtotal . ' </td>
                                </tr>
                                <tr>
                                    <td colspan="5">SHIPPING CHARGES (+)</td>
                                    <td class="total"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> ' . $orderDetails->shipping_charges . ' </td>
                                </tr>
                                <tr>
                                    <td colspan="5">COUPON DISCOUNT (-)</td>
                                    <td class="total"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> ' . $orderDetails->coupon_amount . ' </td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="grand total">GRAND TOTAL</td>
                                    <td class="grand total"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> ' . $orderDetails->grand_total . ' </td>
                                </tr>
                              </tbody>
                            </table>
                          </main>
                        <footer>
                            Invoice was created on a computer and is valid without the signature and seal.
                        </footer>
                      </body>
                    </html>';

        $dompdf = new Dompdf();

        $dompdf->loadHtml($output);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $name = 'Order No-' . $orderDetails->id . '.pdf';
        $dompdf->stream($name);
    }

    // Update Order Status Function

    public function updateOrderStatus(Request $request)
    {
        if(Session::get('adminDetails')['orders_view_access'] == 0 && Session::get('adminDetails')['orders_edit_access'] == 0 && Session::get('adminDetails')['orders_full_access'] == 0)
        {
            return redirect('/admin/dashboard')->with('flash_message_error', 'Sorry, you don\'t have access to this page. How did you manage to come here. Please let us know, so that we can fix this bug.');
        }

        if($request->isMethod('POST'))
        {
            $data = $request->all();

            Order::where('id', $data['order_id'])->update(['order_status' => $data['order_status']]);

            return redirect()->back()->with('flash_message_success', 'Order Status has been Updated Successfully');
        }
    }

    // Front Page Search Function

    public function searchProducts(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $data = $request->all();

            $categories = Category::with('categories')->where(['parent_id' => 0])->get();
            $search_product = $data['product'];
            // $productsAll = Product::where('product_name', 'like', '%' . $search_product . '%')->orwhere('product_code', 'like', '%' . $search_product . '%')->where('status', '1')->get();
            $productsAll = Product::where(function ($query) use ($search_product){
                $query->where('product_name', 'like', '%' . $search_product . '%')->orwhere('product_code', 'like', '%' . $search_product . '%')->orwhere('product_color', 'like', '%' . $search_product . '%')->orwhere('description', 'like', '%' . $search_product . '%');
            })->where('status', 1)->get();

            $banners = Banner::where('status', 1)->get();
            $breadcrumb = "<a href='/'> Home</a> |  <a href='/'>" . ucfirst($search_product) . "</a>";

            return view('products.listing')->with(compact('categories', 'productsAll', 'search_product', 'banners', 'breadcrumb'));
        }

        return redirect('/');
    }

    // Check Pincode Function

    public function checkPincode(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $data = $request->all();

            $pincodeCount = DB::table('pincodes')->where('pincode', $data['pincode'])->count();

            echo $pincodeCount;
        }
    }

    // Filter Products Function

    public function filter(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $data = $request->all();
            $colorUrl = "";
            $sleeveUrl = "";
            $patternUrl = "";
            $sizeUrl = "";

            if(!empty($data['colorFilter']))
            {
                foreach ($data['colorFilter'] as $color)
                {
                    if(empty($colorUrl))
                    {
                        $colorUrl = "color=" . $color;
                    }
                    else
                    {
                        $colorUrl = $colorUrl . "-" . $color;
                    }
                }
            }

            if(!empty($data['sleeveFilter']))
            {
                foreach ($data['sleeveFilter'] as $sleeve)
                {
                    if(empty($sleeveUrl))
                    {
                        $sleeveUrl = "sleeve=" . $sleeve;
                    }
                    else
                    {
                        $sleeveUrl = $sleeveUrl . "-" . $sleeve;
                    }
                }
            }

            if(!empty($data['patternFilter']))
            {
                foreach ($data['patternFilter'] as $pattern)
                {
                    if(empty($patternUrl))
                    {
                        $patternUrl = "pattern=" . $pattern;
                    }
                    else
                    {
                        $patternUrl = $patternUrl . "-" . $pattern;
                    }
                }
            }

            if(!empty($data['sizeFilter']))
            {
                foreach ($data['sizeFilter'] as $size)
                {
                    if(empty($sizeUrl))
                    {
                        $sizeUrl = "size=" . $size;
                    }
                    else
                    {
                        $sizeUrl = $sizeUrl . "-" . $size;
                    }
                }
            }

            if(empty($data['url']) && empty($data['id']))
            {
                $data['url'] = "t-shirts";

                if (empty($colorUrl) && empty($sleeveUrl) && empty($patternUrl) && empty($sizeUrl))
                {
                    $finalUrl = "products/" . $data['url'];
                }
                elseif(empty($sleeveUrl) && empty($patternUrl) && empty($sizeUrl))
                {
                    $finalUrl = "products/" . $data['url'] . "?" . $colorUrl;
                }
                elseif(empty($colorUrl) && empty($patternUrl) && empty($sizeUrl))
                {
                    $finalUrl = "products/" . $data['url'] . "?" . $sleeveUrl;
                }
                elseif(empty($colorUrl) && empty($sleeveUrl) && empty($sizeUrl))
                {
                    $finalUrl = "products/" . $data['url'] . "?" . $patternUrl;
                }
                elseif(empty($colorUrl) && empty($sleeveUrl) && empty($patternUrl))
                {
                    $finalUrl = "products/" . $data['url'] . "?" . $sizeUrl;
                }
                elseif(empty($colorUrl) && empty($sleeveUrl))
                {
                    $finalUrl = "products/" . $data['url'] . "?" . $patternUrl . '&' . $sizeUrl;
                }
                elseif(empty($colorUrl) && empty($patternUrl))
                {
                    $finalUrl = "products/" . $data['url'] . "?" . $sleeveUrl . '&' . $sizeUrl;
                }
                elseif(empty($colorUrl) && empty($sizeUrl))
                {
                    $finalUrl = "products/" . $data['url'] . "?" . $sleeveUrl . '&' . $patternUrl;
                }
                elseif(empty($sleeveUrl) && empty($patternUrl))
                {
                    $finalUrl = "products/" . $data['url'] . "?" . $colorUrl . '&' . $sizeUrl;
                }
                elseif(empty($sleeveUrl) && empty($sizeUrl))
                {
                    $finalUrl = "products/" . $data['url'] . "?" . $colorUrl . '&' . $patternUrl;
                }
                elseif(empty($patternUrl) && empty($sizeUrl))
                {
                    $finalUrl = "products/" . $data['url'] . "?" . $colorUrl . '&' . $sleeveUrl;
                }
                elseif(empty($sleeveUrl))
                {
                    $finalUrl = "products/" . $data['url'] . "?" . $colorUrl . '&' . $patternUrl . '&' . $sizeUrl;
                }
                elseif(empty($colorUrl))
                {
                    $finalUrl = "products/" . $data['url'] . "?" . $sleeveUrl . '&1' . $patternUrl . '&' . $sizeUrl;
                }
                elseif(empty($patternUrl))
                {
                    $finalUrl = "products/" . $data['url'] . "?" . $colorUrl . '&' . $sleeveUrl . '&' . $sizeUrl;
                }
                elseif(empty($sizeUrl))
                {
                    $finalUrl = "products/" . $data['url'] . "?" . $colorUrl . '&' . $sleeveUrl . '&' . $patternUrl;
                }
                else
                {
                    $finalUrl = "products/" . $data['url'] . "?" . $colorUrl . '&' . $sleeveUrl . '&' . $patternUrl . '&' . $sizeUrl;
                }
            }
            else if(empty($data['url']) && !empty($data['id']))
            {
                if (empty($colorUrl) && empty($sleeveUrl) && empty($patternUrl) && empty($sizeUrl))
                {
                    $finalUrl = "product/" . $data['id'];
                }
                elseif(empty($sleeveUrl) && empty($patternUrl) && empty($sizeUrl))
                {
                    $finalUrl = "product/" . $data['id'] . "?" . $colorUrl;
                }
                elseif(empty($colorUrl) && empty($patternUrl) && empty($sizeUrl))
                {
                    $finalUrl = "product/" . $data['id'] . "?" . $sleeveUrl;
                }
                elseif(empty($colorUrl) && empty($sleeveUrl) && empty($sizeUrl))
                {
                    $finalUrl = "product/" . $data['id'] . "?" . $patternUrl;
                }
                elseif(empty($colorUrl) && empty($sleeveUrl) && empty($patternUrl))
                {
                    $finalUrl = "product/" . $data['id'] . "?" . $sizeUrl;
                }
                elseif(empty($colorUrl) && empty($sleeveUrl))
                {
                    $finalUrl = "product/" . $data['id'] . "?" . $patternUrl . '&' . $sizeUrl;
                }
                elseif(empty($colorUrl) && empty($patternUrl))
                {
                    $finalUrl = "product/" . $data['id'] . "?" . $sleeveUrl . '&' . $sizeUrl;
                }
                elseif(empty($colorUrl) && empty($sizeUrl))
                {
                    $finalUrl = "product/" . $data['id'] . "?" . $sleeveUrl . '&' . $patternUrl;
                }
                elseif(empty($sleeveUrl) && empty($patternUrl))
                {
                    $finalUrl = "product/" . $data['id'] . "?" . $colorUrl . '&' . $sizeUrl;
                }
                elseif(empty($sleeveUrl) && empty($sizeUrl))
                {
                    $finalUrl = "product/" . $data['id'] . "?" . $colorUrl . '&' . $patternUrl;
                }
                elseif(empty($patternUrl) && empty($sizeUrl))
                {
                    $finalUrl = "product/" . $data['id'] . "?" . $colorUrl . '&' . $sleeveUrl;
                }
                elseif(empty($sleeveUrl))
                {
                    $finalUrl = "product/" . $data['id'] . "?" . $colorUrl . '&' . $patternUrl . '&' . $sizeUrl;
                }
                elseif(empty($colorUrl))
                {
                    $finalUrl = "product/" . $data['id'] . "?" . $sleeveUrl . '&1' . $patternUrl . '&' . $sizeUrl;
                }
                elseif(empty($patternUrl))
                {
                    $finalUrl = "product/" . $data['id'] . "?" . $colorUrl . '&' . $sleeveUrl . '&' . $sizeUrl;
                }
                elseif(empty($sizeUrl))
                {
                    $finalUrl = "product/" . $data['id'] . "?" . $colorUrl . '&' . $sleeveUrl . '&' . $patternUrl;
                }
                else
                {
                    $finalUrl = "product/" . $data['id'] . "?" . $colorUrl . '&' . $sleeveUrl . '&' . $patternUrl . '&' . $sizeUrl;
                }
            }
            else if(!empty($data['url']) && empty($data['id']))
            {
                if (empty($colorUrl) && empty($sleeveUrl) && empty($patternUrl) && empty($sizeUrl))
                {
                    $finalUrl = "products/" . $data['url'];
                }
                elseif(empty($sleeveUrl) && empty($patternUrl) && empty($sizeUrl))
                {
                    $finalUrl = "products/" . $data['url'] . "?" . $colorUrl;
                }
                elseif(empty($colorUrl) && empty($patternUrl) && empty($sizeUrl))
                {
                    $finalUrl = "products/" . $data['url'] . "?" . $sleeveUrl;
                }
                elseif(empty($colorUrl) && empty($sleeveUrl) && empty($sizeUrl))
                {
                    $finalUrl = "products/" . $data['url'] . "?" . $patternUrl;
                }
                elseif(empty($colorUrl) && empty($sleeveUrl) && empty($patternUrl))
                {
                    $finalUrl = "products/" . $data['url'] . "?" . $sizeUrl;
                }
                elseif(empty($colorUrl) && empty($sleeveUrl))
                {
                    $finalUrl = "products/" . $data['url'] . "?" . $patternUrl . '&' . $sizeUrl;
                }
                elseif(empty($colorUrl) && empty($patternUrl))
                {
                    $finalUrl = "products/" . $data['url'] . "?" . $sleeveUrl . '&' . $sizeUrl;
                }
                elseif(empty($colorUrl) && empty($sizeUrl))
                {
                    $finalUrl = "products/" . $data['url'] . "?" . $sleeveUrl . '&' . $patternUrl;
                }
                elseif(empty($sleeveUrl) && empty($patternUrl))
                {
                    $finalUrl = "products/" . $data['url'] . "?" . $colorUrl . '&' . $sizeUrl;
                }
                elseif(empty($sleeveUrl) && empty($sizeUrl))
                {
                    $finalUrl = "products/" . $data['url'] . "?" . $colorUrl . '&' . $patternUrl;
                }
                elseif(empty($patternUrl) && empty($sizeUrl))
                {
                    $finalUrl = "products/" . $data['url'] . "?" . $colorUrl . '&' . $sleeveUrl;
                }
                elseif(empty($sleeveUrl))
                {
                    $finalUrl = "products/" . $data['url'] . "?" . $colorUrl . '&' . $patternUrl . '&' . $sizeUrl;
                }
                elseif(empty($colorUrl))
                {
                    $finalUrl = "products/" . $data['url'] . "?" . $sleeveUrl . '&' . $patternUrl . '&' . $sizeUrl;
                }
                elseif(empty($patternUrl))
                {
                    $finalUrl = "products/" . $data['url'] . "?" . $colorUrl . '&' . $sleeveUrl . '&' . $sizeUrl;
                }
                elseif(empty($sizeUrl))
                {
                    $finalUrl = "products/" . $data['url'] . "?" . $colorUrl . '&' . $sleeveUrl . '&' . $patternUrl;
                }
                else
                {
                    $finalUrl = "products/" . $data['url'] . "?" . $colorUrl . '&' . $sleeveUrl . '&' . $patternUrl . '&' . $sizeUrl;
                }
            }

            return redirect::to($finalUrl);
        }
    }

    // Export Users Function

    public function exportProducts()
    {
        return Excel::download(new productsExport,'Products ' . date('d-M-Y h:i:s') . '.xlsx');
    }

    // View Wish List Function

    public function wishList()
    {
        $user_email = Auth::user()->email;
        $user_wishList = DB::table('wish_list')->where('user_email', $user_email)->get();

        foreach($user_wishList as $key => $product)
        {
            $productDetails = Product::where('id', $product->product_id)->first();
            $user_wishList[$key]->image = $productDetails->image;
        }

        $meta_title = "Wish List";
        $meta_description = "Wish List for Shopping";
        $meta_keywords = "wish-list,shopping";

        return view('products.wish_list')->with(compact('user_wishList', 'meta_title', 'meta_description', 'meta_keywords'));
    }

    // Delete Wish List Function

    public function deleteWishList($id = null)
    {
        DB::table('wish_list')->where('id', $id)->delete();

        return redirect('wish-list')->with('flash_message_success', 'Removed from Wish List Successfully');
    }

    // View Order Charts Function

    public function viewOrdersAnalysis()
    {
        $orderCount = Order::count();
        $currentMonthOrders = Order::whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->month)->count();
        $lastMonthOrders = Order::whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->subMonth(1))->count();
        $lastPreviousMonthOrders = Order::whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->subMonth(2))->count();
        $lastFourMonthOrders = Order::whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->subMonth(3))->count();
        $lastFiveMonthOrders = Order::whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->subMonth(4))->count();

        return view('admin.products.view_orders_analysis')->with(compact('orderCount', 'currentMonthOrders', 'lastMonthOrders', 'lastPreviousMonthOrders', 'lastFourMonthOrders', 'lastFiveMonthOrders'));
    }
}
