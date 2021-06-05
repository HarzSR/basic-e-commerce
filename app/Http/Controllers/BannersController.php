<?php

namespace App\Http\Controllers;

use App\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Image;
use Validator;

class BannersController extends Controller
{
    // Add Banner Function

    public function addBanner(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $data = $request->all();

            $validator = Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg',
                'title' => 'required|regex:/^[\w\-\s]+$/',
                'link' => 'required|regex:/(?:.*\/)(?:[^.]+$)/'
            ]);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput($request->input());
            }

            $banner = new Banner();
            if (empty($data['image']))
            {
                $banner->image = '';
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

    // View Banners Function

    public function viewBanners()
    {
        $banners = Banner::get();
        $bannerCount = Banner::count();

        return view('admin.banners.view_banners')->with(compact('banners', 'bannerCount'));
    }

    // Edit Banner Function

    public function editBanner(Request $request, $id = null)
    {
        $bannerDetails = Banner::where('id', $id)->first();

        if($request->isMethod('POST'))
        {
            $data = $request->all();

            $validator = Validator::make($request->all(), [
                'current_image' => 'required',
                'title' => 'required|regex:/^[\w\-\s]+$/',
                'link' => 'required|regex:/(?:.*\/)(?:[^.]+$)/'
            ]);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput($request->input());
            }

            $banner = new Banner();
            if ($request->hasFile('image'))
            {
                $image_tmp = $request->file('image');

                if ($image_tmp->isValid())
                {
                    $extension = $image_tmp->getClientOriginalExtension();
                    $fileName = time() . mt_rand() . '.' . $extension;

                    $banner_path = 'images/frontend_images/banners/' . $fileName;

                    Image::make($image_tmp)->resize(1140, 340)->save($banner_path);
                }
            }
            else if(!empty($data['current_image']))
            {
                $fileName = $data['current_image'];
            }
            else
            {
                $fileName = '';
            }
            if(empty($data['title']))
            {
                $data['title'] = '';
            }
            if(empty($data['link']))
            {
                $data['link'] = '';
            }
            if(empty($data['status']))
            {
                $status = 0;
            }
            else
            {
                $status = 1;
            }

            Banner::where('id', $id)->update(['image' => $fileName, 'title' => $data['title'], 'link' => $data['link'], 'status' => $status]);

            return redirect()->back()->with('flash_message_success', 'Banner Edited Successfully');
        }

        return view('admin.banners.edit_banner')->with(compact('bannerDetails'));
    }

    // Delete Banner Function

    public function deleteBanner($id = null)
    {
        $banner = Banner::where(['id' => $id])->first();

        $banner_image_path = 'images/frontend_images/banners/' . $banner->image;

        // File::delete($banner_image_path);
        if (file_exists($banner_image_path) && !empty($banner->image))
        {
            unlink($banner_image_path);
        }

        Banner::where(['id' => $id])->delete();

        return redirect()->back()->with('flash_message_success', 'Product removed successfully');
    }
}
