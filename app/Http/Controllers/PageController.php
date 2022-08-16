<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Asset;
use Auth;
use Illuminate\Support\Facades\Http;

class PageController extends Controller
{
    public function index()
    {
        return Page::with('user:id,firstname,lastname,name')->latest()->get();
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $inputs = $request->all();
        $inputs['user_id'] = $user->id;

        $this->validate($request, [
            'title' => 'required',
            'slug' => 'required',
            'type' => 'required',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:2048',
            'content' => 'required',
            'markdown' => 'required',
            'status' => 'required',
            'meta_description' => 'nullable',
            'meta_tags' => 'nullable',
            'category' => 'nullable',
            'excerpt' => 'nullable',
        ]);

        $file = null;

        $slug = Page::where('slug', $request->slug)->exists();

        if ($slug) {
            return response()->json([
                'errors' => ['slug' => ['The url already exists in our records. Please choose a new one.']]
            ], 422);  
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file = $image->store('images', 'public');
        }

        $inputs['image'] = $file;

        $page = Page::create($inputs);

        $response = Http::get('https://www.google.com/ping?sitemap=https://api.anshell.com/sitemap.xml');

        $this->bing($page);

        return response()->json(['message' => 'Page added successfully.', 'id' => $page->id]);
    }

    public function update(Request $request, $id)
    {
        $page = Page::find($id);

        $user = Auth::user();

        $inputs = $request->all();

        $this->validate($request, [
            'title' => 'required',
            'slug' => 'required',
            'type' => 'required',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:2048',
            'content' => 'required',
            'markdown' => 'required',
            'status' => 'required',
            'meta_description' => 'nullable',
            'meta_tags' => 'nullable',
            'category' => 'nullable',
            'excerpt' => 'nullable',
        ]);

        $file = null;

        $slug = Page::where('slug', $request->slug)->exists();
        $page_ = Page::where('slug', $request->slug)->first();

        if ($slug && $page_->id != $page->id) {
            return response()->json([
                'errors' => ['slug' => ['The url already exists in our records. Please choose a new one.']]
            ], 422);  
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file = $image->store('images', 'public');
            // $imgName = random_int(111111, 999999) . '.' . $image->getClientOriginalExtension();
            // $file = $image->move('uploads/images', $imgName);
            $page->image = $file;
            $page->save();
        }

        $page->title = $request->title;
        $page->slug = $request->slug;
        $page->type = $request->type;
        $page->content = $request->content;
        $page->markdown = $request->markdown;
        $page->status = $request->status;
        $page->meta_description = $request->meta_description;
        $page->meta_tags = $request->meta_tags;
        $page->excerpt = $request->excerpt;
        $page->category = $request->category;
        $page->save();

        return response()->json(['message' => 'Page updated successfully.', 'id' => $page->id]);
    }

    public function show($id)
    {
        $page = Page::where('id', $id)->first();

        return $page;
    }

    public function getPage($slug)
    {
        $page = Page::where('slug', $slug)->first();

        if (!empty($page)) {
            return $page;
        } else {
            return (object)[];
        }
    }

    public function blogs()
    {
        return Page::where('type', 'blog')->where('status', 'true')->get();
    }

    public function getBlog($slug)
    {
        return Page::where('type', 'blog')->with('user:id,name')->where('slug', $slug)->where('status', 'true')->first();
    }

    public function destroy($id)
    {
        if (Auth::check()) {
            $page = Page::find($id);
            if ($page->delete()) {
                $response = Http::get('https://www.google.com/ping?sitemap=https://api.anshell.com/sitemap.xml');
                return response()->json(['status' => true]);
            }
        }
    }

    public function upload(Request $request)
    {
        $user = Auth::user();
        
        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $file = $image->store('assets', 'public');
            
            $asset = new Asset();
            $asset->user_id = $user->id;
            $asset->image = $file;
            $asset->status = 'true';

            if ($asset->save()) {
                return asset('uploads/' . $file);
            }
        }
    }

    public function bing($data)
    {
        $siteurl = 'https://anshell.com';
        $slug =  $siteurl .'/'. $data->slug;

        $curl = curl_init();
        $links = [];
        $links[] = $slug;

        $data = new stdClass();
        $data->siteUrl = "https://anshell.com";
        $data->urlList = $links;

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://ssl.bing.com/webmaster/api.svc/json/SubmitUrlbatch?apikey=8a68ae5d8e014b90bea6f1a3dc33f7a2',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Cookie: MUID=20DF0567ECFD6A8D0AB71467ED936BD3; _EDGE_S=F=1&SID=107CAEEF3B7D6F4D1FF7BFEF3A136EF5; _EDGE_V=1; MUIDB=20DF0567ECFD6A8D0AB71467ED936BD3'
        ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        logger('Submittted to bing');
    }
}

