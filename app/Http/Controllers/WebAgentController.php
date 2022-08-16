<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\WebAgent;
use Auth;
use Illuminate\Support\Facades\Http;

class WebAgentController extends Controller
{
    public function index()
    {
        $agents = WebAgent::get();

        return $this->paginate($agents);
    }

    public function show($slug)
    {
        return WebAgent::where('slug', $slug)->first();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'slug' => 'required',
            'email' => 'required|email:filter|max:255',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:2048'
        ]);

        $slug = WebAgent::where('slug', $request->slug)->exists();

        if ($slug) {
            return response()->json([
                'errors' => ['slug' => ['The slug already exists in our records. Please choose a new one.']]
            ], 422);  
        }

        $inputs = $request->all();
        $inputs['name'] = $request->firstname . ' ' . $request->lastname;
        $file = null;

        if (Auth::user()->role != 'user') {

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $file = $image->store('images/agents', 'public');

                $inputs['image'] = $file;
            }

            $user = WebAgent::create($inputs);

            $response = Http::get('https://www.google.com/ping?sitemap=https://api.anshell.com/sitemap.xml');
            $this->bing($user);

            return response()->json(['message' => 'Agent created.']);
        }
    }

    public function update(Request $request)
    {
        $agent = WebAgent::find($request->id);

        $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'slug' => 'required',
            'email' => 'required|email:filter|max:255',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:2048'
        ]);

        $slug = WebAgent::where('slug', $request->slug)->exists();
        $agent_ = WebAgent::where('slug', $request->slug)->first();

        if ($slug && $agent_->id != $agent->id) {
            return response()->json([
                'errors' => ['slug' => ['The url already exists in our records. Please choose a new one.']]
            ], 422);  
        }

        $inputs = $request->all();
        $inputs['name'] = $request->firstname . ' ' . $request->lastname;
        $file = null;

        $inputs['image'] = $request->image == null ? str_replace(asset('uploads').'/', '', $agent->image) : null;

        if (Auth::user()->role != 'user') {

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $file = $image->store('images/agents', 'public');

                $inputs['image'] = $file;
            }

            WebAgent::where('id', $agent->id)
                ->update($inputs);

            $response = Http::get('https://www.google.com/ping?sitemap=https://api.anshell.com/sitemap.xml');

            return response()->json(['message' => 'Agent updated.']);
        }
    }

    public function setAgent($id)
    {
        $webagent = WebAgent::find($id);
        
        $agents = WebAgent::get();

        if (Auth::user()->role != 'user') {
            foreach ($agents as $key => $agent) {
                $agent->primary = 0;
                $agent->save();
            }

            $webagent->primary = $webagent->primary == 1 ? 0 : 1;

            if ($webagent->save()) {
                return response()->json(['message' => 'Agent was set to primary.']);
            }
        }
    }

    public function getPrimary()
    {
        return WebAgent::where('primary', 1)->first();
    }

    public function destroy($id)
    {
        $agent = WebAgent::find($id);
        
        if (Auth::user()->role != 'user') {
            if ($agent->delete()) {
                return response()->json(['message' => 'Agent deleted.']);
            }
        }

        $response = Http::get('https://www.google.com/ping?sitemap=https://api.anshell.com/sitemap.xml');
    }

    public function paginate($items, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function bing($data)
    {
        $siteurl = 'https://anshell.com';
        $slug =  $siteurl .'/agent/'. $data->slug;

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
