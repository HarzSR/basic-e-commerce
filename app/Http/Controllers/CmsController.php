<?php

namespace App\Http\Controllers;

use App\CmsPage;
use Illuminate\Http\Request;

class CmsController extends Controller
{
    //

    public function addCmsPage(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $data = $request->all();

            $cmsPage = new CmsPage;
            $cmsPage->title = $data['title'];
            $cmsPage->url = $data['url'];
            $cmsPage->description = $data['description'];
            if(empty($data['status']))
            {
                $cmsPage->status = 0;
            }
            else
            {
                $cmsPage->status = 1;
            }
            $cmsPage->save();

            return redirect()->back()->with('flash_message_success', 'CMS Page Added Successfully');
        }

        return view('admin.pages.add_cms_page');
    }
}
