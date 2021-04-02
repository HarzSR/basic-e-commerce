<?php

namespace App\Http\Controllers;

use App\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Image;

class BannersController extends Controller
{
    // Add Banner Function

    public function addBanner(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $data = $request->all();

            $banner = new Banner();
            if (empty($data['image']))
            {
                $banner->image = '';
            }
            else
            {
                if ($request->hasFile('image'))
                {
                    $image_tmp = Input::file('image');

                    if ($image_tmp->isValid())
                    {
                        $extension = $image_tmp->getClientOriginalExtension();
                        $fileName = time() . mt_rand() . '.' . $extension;

                        $banner_path = 'images/frontend_images/banners/' . $fileName;

                        Image::make($image_tmp)->resize(1140, 340)->save($banner_path);

                        $banner->image = $fileName;
                    }
                }
            }
            $banner->title = $data['title'];
            $banner->link = $data['link'];
            if (empty($data['status']))
            {
                $banner->status = 0;
            }
            else
            {
                $banner->status = 1;
            }

            $banner->save();

            return redirect()->back()->with('flash_message_success', 'Banner Added Successfully');
        }

        return view('admin.banners.add_banner');
    }
}
