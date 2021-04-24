<?php

namespace App\Http\Controllers;

use App\Banner;
use App\Category;
use App\Product;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    //

    public function index()
    {
        // Get All Products - Ascending

        // $productsAll = Product::get();

        // Get All Products - Descending

        // $productsAll = Product::orderBy('id' , 'DESC')->get();

        // Get All Products - Random

        $productsFeaturedAll = Product::inRandomOrder()->where('status', 1)->where('feature_item', 1)->take(3)->get();
        $productsAll = Product::inRandomOrder()->where('status', 1)->where('feature_item', 0)->get();

        // Get Sub Categories

        /*

        // General Method

        $categories = Category::where(['parent_id' => 0])->get();

        $category_menu = "";

        foreach($categories as $category)
        {
            $category_menu .= '<div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#' . $category->id . '" href="#' . $category->url . '">
                        <span class="badge pull-right"><i class="fa fa-plus"></i></span>
                            ' . $category->name . '
                    </a>
                </h4>
            </div>
            <div id="' . $category->url . '" class="panel-collapse collapse">
                <div class="panel-body">
                    <ul>';

            $sub_categories = Category::where(['parent_id' => $category->id])->get();
            foreach($sub_categories as $sub_category)
            {
                $category_menu .= '<li><a href="#">' . $sub_category->name . '</a></li>';
            }

            $category_menu .= '</ul>
                </div>
            </div>';
        }
        */

        $categories = Category::with('categories')->where(['parent_id' => 0])->get();

        $banners = Banner::where('status', 1)->get();

        return view('index')->with(compact('productsFeaturedAll', 'productsAll' , 'categories', 'banners'));
    }
}
