<?php

namespace App\Http\Controllers;

use App\CmsPage;
use Illuminate\Http\Request;

class CmsController extends Controller
{
    // Add CMS Page Function

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

    // View CMS Page Function

    public function viewCmsPage()
    {
        $cmsPages = CmsPage::get();
        $cmsPageCount = CmsPage::count();

        return view('admin.pages.view_cms_pages')->with(compact('cmsPages', 'cmsPageCount'));
    }
}
