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

    public function viewCmsPages()
    {
        $cmsPages = CmsPage::get();
        $cmsPageCount = CmsPage::count();

        return view('admin.pages.view_cms_pages')->with(compact('cmsPages', 'cmsPageCount'));
    }

    // Edit CMS Page Function

    public function editCmsPage(Request $request, $id = null)
    {
        if($request->isMethod('POST'))
        {
            $data = $request->all();

            if(empty($data['status']))
            {
                $status = 0;
            }
            else
            {
                $status = 1;
            }

            CmsPage::where('id', $id)->update(['title' => $data['title'], 'url' => $data['url'], 'description' => $data['description'], 'status' => $status]);

            return redirect('/admin/view-cms-pages')->with('flash_message_success', 'CMS Page Updated Successfully');
        }

        $cmsPage = CmsPage::where('id', $id)->first();

        return view('admin.pages.edit_cms_page')->with(compact('cmsPage'));
    }

    // Delete CMS Page Function

    public function deleteCmsPage($id = null)
    {
        CmsPage::where('id', $id)->delete();

        return redirect('/admin/view-cms-pages')->with('flash_message_success', 'CMS Page Deleted Successfully');
    }
}
