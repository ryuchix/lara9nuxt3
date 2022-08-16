<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Seo;

class SettingController extends Controller
{
    public function save(Request $request)
    {
        $inputs = $request->all();

        $exists = Setting::where('id', 1)->exists();

        if ($exists) {
            $setting = Setting::where('id', 1)->update($inputs);
            if ($setting) {
                return response()->json(['message' => 'Updated successfully.']);
            }
        } else {
            $setting = Setting::create($inputs);
            if ($setting) {
                return response()->json(['message' => 'Updated successfully.']);
            }
        }
    }

    public function show()
    {
        return Setting::find(1) ?? [];
    }

    public function saveSeo(Request $request)
    {
        $inputs = $request->all();

        $exists = Seo::where('id', 1)->exists();
        $seos = Seo::find(1);

        if ($request->hasFile('website_logo')) {
            $image = $request->file('website_logo');
            $file = $image->store('images/seo', 'public');
 
            $inputs['website_logo'] = $file;
        } else {
            $inputs['website_logo'] = $request->website_logo == null ? $seos->getRawOriginal('website_logo') : null;
        }

        if ($request->hasFile('website_image')) {
            $image = $request->file('website_image');
            $file = $image->store('images/seo', 'public');
 
            $inputs['website_image'] = $file;
        } else {
            $inputs['website_image'] = $request->website_image == null ? $seos->getRawOriginal('website_image') : null;
        }

        if ($request->hasFile('schema_person_image')) {
            $image = $request->file('schema_person_image');
            $file = $image->store('images/seo', 'public');
 
            $inputs['schema_person_image'] = $file;
        } else {
            $inputs['schema_person_image'] = $request->schema_person_image == null ? $seos->getRawOriginal('schema_person_image') : null;
        }

        if ($request->hasFile('schema_business_image')) {
            $image = $request->file('schema_business_image');
            $file = $image->store('images/seo', 'public');
 
            $inputs['schema_business_image'] = $file;
        } else {
            $inputs['schema_business_image'] = $request->schema_business_image == null ? $seos->getRawOriginal('schema_business_image') : null;
        }

        if ($request->hasFile('sell_image')) {
            $image = $request->file('sell_image');
            $file = $image->store('images/seo', 'public');
 
            $inputs['sell_image'] = $file;
        } else {
            $inputs['sell_image'] = $request->sell_image == null ? $seos->getRawOriginal('sell_image') : null;
        }

        if ($request->hasFile('agents_image')) {
            $image = $request->file('agents_image');
            $file = $image->store('images/seo', 'public');
 
            $inputs['agents_image'] = $file;
        } else {
            $inputs['agents_image'] = $request->agents_image == null ? $seos->getRawOriginal('agents_image') : null;
        }

        if ($request->hasFile('listing_state_image')) {
            $image = $request->file('listing_state_image');
            $file = $image->store('images/seo', 'public');
 
            $inputs['listing_state_image'] = $file;
        } else {
            $inputs['listing_state_image'] = $request->listing_state_image == null ? $seos->getRawOriginal('listing_state_image') : null;
        }

        if ($request->hasFile('listing_city_image')) {
            $image = $request->file('listing_city_image');
            $file = $image->store('images/seo', 'public');
 
            $inputs['listing_city_image'] = $file;
        } else {
            $inputs['listing_city_image'] = $request->listing_city_image == null ? $seos->getRawOriginal('listing_city_image') : null;
        }

        if ($request->hasFile('contact_us_image')) {
            $image = $request->file('contact_us_image');
            $file = $image->store('images/seo', 'public');
 
            $inputs['contact_us_image'] = $file;
        } else {
            $inputs['contact_us_image'] = $request->contact_us_image == null ? $seos->getRawOriginal('contact_us_image') : null;
        }

        if ($exists) {
            $seo = Seo::where('id', 1)->update($inputs);
            if ($seo) {
                return response()->json(['message' => 'Updated successfully.']);
            }
        } else {
            $seo = Seo::create($inputs);
            if ($seo) {
                return response()->json(['message' => 'Updated successfully.']);
            }
        }
    }

    public function seo()
    {
        return Seo::find(1) ?? [];
    }   
}
