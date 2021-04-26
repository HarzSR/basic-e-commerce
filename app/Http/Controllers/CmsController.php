<?php

namespace App\Http\Controllers;

use App\CmsPage;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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

    // Display CMS Page Function

    public function cmsPage(Request $request, $url = null)
    {
        if($request->isMethod('POST'))
        {
            $data = $request->all();
        }

        $cmsPageCount = CmsPage::where(['url' => $url, 'status' => 1])->count();

        if($cmsPageCount == 1)
        {
            $cmsPageDetails = CmsPage::where('url', $url)->first();
        }
        else
        {
            abort(404);
        }

        return view('pages.cms_page')->with(compact('cmsPageDetails'));
    }

    // Contact Us Function

    public function contact(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $data = $request->all();

            $validator = Validator::make($request->all(), [
                'name' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
                'email' => 'required|email',
                'subject' => 'required',
                'message' => 'required'
            ]);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput($request->input());
            }

            $email = "hariharansmm@gmail.com";
            $messageData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'subject' => $data['subject'],
                'comment' => $data['message']
            ];
            Mail::send('emails.enquiry', $messageData, function ($message) use ($email){
                $message->to($email)->subject('Enquiry - Contact Form');
            });

            return redirect()->back()->with('flash_message_success', 'Thank you for your enquiry. We will get back to you soon.');
        }

        $meta_title = "E Commerce Shopping";
        $meta_description = "Online shopping for Men, Women, Boys, Girls, Children";
        $meta_keywords = "online shopping, sale, e-commerce, buy online";

        return view('pages.contact')->with(compact('meta_title', 'meta_description', 'meta_keywords'));
    }
}
