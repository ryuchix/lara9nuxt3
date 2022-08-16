<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\WebAgent;
use App\Models\Property;
use App\Models\House;
use App\Models\AlertUser;
use App\Models\Favorite;
use App\Models\Page;
use App\Models\State;
use App\Models\City;

use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

use Auth;

class ListingController extends Controller
{

    protected $states = [
        'AL' => "Alabama",  
        'AK' => "Alaska",  
        'AZ' => "Arizona",  
        'AR' => "Arkansas",  
        'CA' => "California",  
        'CO' => "Colorado",  
        'CT' => "Connecticut",  
        'DE' => "Delaware",  
        'DC' => "District Of Columbia",  
        'FL' => "Florida",  
        'GA' => "Georgia",  
        'HI' => "Hawaii",  
        'ID' => "Idaho",  
        'IL' => "Illinois",  
        'IN' => "Indiana",  
        'IA' => "Iowa",  
        'KS' => "Kansas",  
        'KY' => "Kentucky",  
        'LA' => "Louisiana",  
        'ME' => "Maine",  
        'MD' => "Maryland",  
        'MA' => "Massachusetts",  
        'MI' => "Michigan",  
        'MN' => "Minnesota",  
        'MS' => "Mississippi",  
        'MO' => "Missouri",  
        'MT' => "Montana",
        'NE' => "Nebraska",
        'NV' => "Nevada",
        'NH' => "New Hampshire",
        'NJ' => "New Jersey",
        'NM' => "New Mexico",
        'NY' => "New York",
        'NC' => "North Carolina",
        'ND' => "North Dakota",
        'OH' => "Ohio",  
        'OK' => "Oklahoma",  
        'OR' => "Oregon",  
        'PA' => "Pennsylvania",  
        'RI' => "Rhode Island",  
        'SC' => "South Carolina",  
        'SD' => "South Dakota",
        'TN' => "Tennessee",  
        'TX' => "Texas",  
        'UT' => "Utah",  
        'VT' => "Vermont",  
        'VA' => "Virginia",  
        'WA' => "Washington",  
        'WV' => "West Virginia",  
        'WI' => "Wisconsin",  
        'WY' => "Wyoming"
    ];

    public function home()
    {
        try {
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }

            $response = Http::acceptJson()->get('https://ipinfo.io/' . $ip . '/json?token=10f9001a70d669');
            $res = json_decode($response->body(), true);
            $region = $res['region'] ?? 'Florida';

            $properties = Property::take(5)->where('StateOrProvince', $region == 'New York' || $region == 'New Jersey' ? 'NY' : 'FL')->where('City', '!=', null)->latest()->get();
            
            $list = [];
            foreach ($properties as $property) {
                $property['state'] = $this->states[$property->StateOrProvince];
            }

            return $properties;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    
    public function listings()
    {
        return Property::paginate();
    }
    
    public function listingsApi()
    {
        $properties = Property::where('source', 'web')->get();

        foreach ($properties as $property) {
            $property['Media'] = json_decode($property->Media ?? '')[0] ?? [];
        }

        return $properties;
    }

    public function deleteListingImage(Request $request, $id)
    {
        $image = $request->image;
        $property = Property::where('ListingId', $id)->first();
        
        $medias = json_decode($property->Media, true);

        foreach($medias as $subKey => $key) {
            if($key['MediaURL'] == $image) {
                unset($medias[$subKey]);
            }
        }

        $property->Media = json_encode($medias, true);
        $property->save();

        return $medias;
    }
    
    public function showListingsApi($id)
    {
        $property = Property::where('ListingId', $id)->first();

        if (empty($property)) {
            return;
        }

        $property['UnparsedAddress'] = $property->UnparsedAddress;
        $property['BedroomsTotal'] = $property->BedroomsTotal;
        $property['BathroomsTotalDecimal'] = $property->BathroomsTotalDecimal;
        $property['ListPrice'] = $property->ListPrice;
        $property['PropertyType'] = $property->PropertyType;
        $property['StateOrProvince'] = $property->StateOrProvince;
        $property['City'] = $property->City;
        $property['PostalCode'] = $property->PostalCode;
        $property['Latitude'] = $property->Latitude;
        $property['Longitude'] = $property->Longitude;
        $property['StreetName'] = $property->StreetName;
        $property['ExpirationDate'] = $property->ExpirationDate;
        $property['PublicRemarks'] = $property->PublicRemarks;
        $property['CommunityFeatures'] = implode(',', json_decode($property->CommunityFeatures) ?? []) ?? '';
        $property['StructureType'] = implode(',', json_decode($property->StructureType) ?? []) ?? '';
        $property['AssociationAmenities'] = implode(',', json_decode($property->AssociationAmenities) ?? []) ?? '';
        $property['HighSchool'] = $property->HighSchool;
        $property['MiddleOrJuniorSchool'] = $property->MiddleOrJuniorSchool;
        $property['ElementarySchool'] = $property->ElementarySchool;
        $property['AssociationFee'] = $property->AssociationFee;
        $property['CountyOrParish'] = $property->CountyOrParish;
        $property['ParkingFeatures'] = implode(',', json_decode($property->ParkingFeatures) ?? []) ?? '';
        $property['ConstructionMaterials'] = implode(',', json_decode($property->ConstructionMaterials) ?? []) ?? '';
        $property['LotFeatures'] = implode(',', json_decode($property->LotFeatures) ?? []) ?? '';
        $property['Roof'] = implode(',', json_decode($property->Roof) ?? []) ?? '';
        $property['PatioAndPorchFeatures'] = implode(',', json_decode($property->PatioAndPorchFeatures) ?? []) ?? '';
        $property['FireplaceYN'] = $property->FireplaceYN;
        $property['Flooring'] = implode(',', json_decode($property->Flooring) ?? []) ?? '';
        $property['Basement'] = implode(',', json_decode($property->Basement) ?? []) ?? '';
        $property['Heating'] = implode(',', json_decode($property->Heating) ?? []) ?? '';
        $property['InteriorFeatures'] = implode(',', json_decode($property->InteriorFeatures) ?? []) ?? '';
        $property['Appliances'] = implode(',', json_decode($property->Appliances) ?? []) ?? '';
        $property['SecurityFeatures'] = implode(',', json_decode($property->SecurityFeatures) ?? []) ?? '';
        $property['PropertySubType'] = $property->PropertySubType;
        $property['Utilities'] = implode(',', json_decode($property->Utilities) ?? []) ?? '';
        $property['ArchitecturalStyle'] = implode(',', json_decode($property->ArchitecturalStyle) ?? []) ?? '';
        $property['YearBuiltDetails'] = $property->YearBuiltDetails;
        $property['YearBuilt'] = $property->YearBuilt;
        $property['LivingArea'] = $property->LivingArea;
        $property['Media'] = json_decode($property->Media);

        return $property;
    }

    public function deleteListingApi($id)
    {
        if (Auth::check()) {
            $property = Property::find($id);
            if ($property->delete()) {
                $response = Http::get('https://www.google.com/ping?sitemap=https://api.anshell.com/sitemap.xml');
                return response()->json(['status' => true]);
            }
        }

    }
    
    public function updateListingsApi(Request $request, $id)
    {
        $property = Property::where('ListingId', $id)->first();

        if ($request->hasFile('image')) {
            $files = [];

            foreach ($request->file('image') as $image) {
                $file = $image->store('images/property/'.$property->id, 'public');

                $files[]['MediaURL'] = $file;
            }

            if (json_decode($property->Media) != null) {
                foreach (json_decode($property->Media) as $media) {
                    $files[]['MediaURL'] = $media->MediaURL;
                }
            }

            $property->Media = json_encode($files, true);

            $property->save();
        }

        $inputs = $request->except(['image']);
        $inputs['FireplaceYN'] = $request->FireplaceYN  == 'true' ? 1 : 0;
        $inputs['CommunityFeatures'] = json_encode(explode(",", $request->CommunityFeatures) ?? []);
        $inputs['StructureType'] = json_encode(explode(",", $request->StructureType) ?? []);
        $inputs['AssociationAmenities'] = json_encode(explode(",", $request->AssociationAmenities) ?? []);
        $inputs['ParkingFeatures'] = json_encode(explode(",", $request->ParkingFeatures) ?? []);
        $inputs['ConstructionMaterials'] = json_encode(explode(",", $request->ConstructionMaterials) ?? []);
        $inputs['LotFeatures'] = json_encode(explode(",", $request->LotFeatures) ?? []);
        $inputs['Roof'] = json_encode(explode(",", $request->Roof) ?? []);
        $inputs['PatioAndPorchFeatures'] = json_encode(explode(",", $request->PatioAndPorchFeatures) ?? []);
        $inputs['Flooring'] = json_encode(explode(",", $request->Flooring) ?? []);
        $inputs['Basement'] = json_encode(explode(",", $request->Basement) ?? []);
        $inputs['Heating'] = json_encode(explode(",", $request->Heating) ?? []);
        $inputs['InteriorFeatures'] = json_encode(explode(",", $request->InteriorFeatures) ?? []);
        $inputs['Appliances'] = json_encode(explode(",", $request->Appliances) ?? []);
        $inputs['SecurityFeatures'] = json_encode(explode(",", $request->SecurityFeatures) ?? []);
        $inputs['Utilities'] = json_encode(explode(",", $request->Utilities) ?? []);
        $inputs['ArchitecturalStyle'] = json_encode(explode(",", $request->ArchitecturalStyle) ?? []);

        Property::where('ListingId', $id)->update($inputs);

        $exists = City::where('name', $property->City)->where('state', $this->states[$property->StateOrProvince])->exists();
        if (!$exists) {
            City::create([
                'name' => $property->City,
                'state' => $this->states[$property->StateOrProvince]
            ]);
        }

        return response()->json(['message' => 'Property updated successfully.', 'id' => $property->id]);
    }

    public function addListingsApi(Request $request)
    {
        $inputs = $request->all();
        $inputs['source'] = 'web';
        $inputs['ListingId'] = 'NY' . mt_rand(10000000, 99999999);
        $inputs['FireplaceYN'] = $request->FireplaceYN  == 'true' ? 1 : 0;
        $inputs['CommunityFeatures'] = json_encode(explode(",", $request->CommunityFeatures) ?? []);
        $inputs['StructureType'] = json_encode(explode(",", $request->StructureType) ?? []);
        $inputs['AssociationAmenities'] = json_encode(explode(",", $request->AssociationAmenities) ?? []);
        $inputs['ParkingFeatures'] = json_encode(explode(",", $request->ParkingFeatures) ?? []);
        $inputs['ConstructionMaterials'] = json_encode(explode(",", $request->ConstructionMaterials) ?? []);
        $inputs['LotFeatures'] = json_encode(explode(",", $request->LotFeatures) ?? []);
        $inputs['Roof'] = json_encode(explode(",", $request->Roof) ?? []);
        $inputs['PatioAndPorchFeatures'] = json_encode(explode(",", $request->PatioAndPorchFeatures) ?? []);
        $inputs['Flooring'] = json_encode(explode(",", $request->Flooring) ?? []);
        $inputs['Basement'] = json_encode(explode(",", $request->Basement) ?? null);
        $inputs['Heating'] = json_encode(explode(",", $request->Heating) ?? []);
        $inputs['InteriorFeatures'] = json_encode(explode(",", $request->InteriorFeatures) ?? []);
        $inputs['Appliances'] = json_encode(explode(",", $request->Appliances) ?? []);
        $inputs['SecurityFeatures'] = json_encode(explode(",", $request->SecurityFeatures) ?? []);
        $inputs['Utilities'] = json_encode(explode(",", $request->Utilities) ?? []);
        $inputs['ArchitecturalStyle'] = json_encode(explode(",", $request->ArchitecturalStyle) ?? []);

        $property = Property::create($inputs);

        $exists = City::where('name', $property->City)->where('state', $this->states[$property->StateOrProvince])->exists();
        if (!$exists) {
            City::create([
                'name' => $property->City,
                'state' => $this->states[$property->StateOrProvince]
            ]);
        }

        $response = Http::get('https://www.google.com/ping?sitemap=https://api.anshell.com/sitemap.xml');
        $this->bing($property);
        if ($request->hasFile('image')) {
            $files = [];

            foreach ($request->file('image') as $image) {
                $file = $image->store('images/property/'.$property->id, 'public');

                $files[]['MediaURL'] = $file;
            }
            $property->Media = json_encode($files, true);
            $property->save();
        }

        return response()->json(['message' => 'Property added successfully.', 'id' => $property->id]);
    }

    public function showListing($id)
    {
        $property = Property::where('ListingId', $id)->with('openhouse')->first();

        if (empty($property)) {
            return;
        }

        $agent = WebAgent::find($property->ListAgentMlsId);

        $cities = Property::where('StateOrProvince', $property->StateOrProvince)
                        ->selectRaw('AVG(ListPrice) average, City, count(City) as total')
                        ->groupBy('City')
                        ->where('City', '!=', $property->City)
                        ->inRandomOrder()
                        ->take(6)
                        ->get();

        $property['type'] = array_filter([$property->PropertyType, $property->PropertySubType]);
        $property['related'] = $this->showRelated($property->ListingId, $property->StateOrProvince, $property->City, $property->PostalCode);
        $property['openhouse'] = $this->openHouse($property->ListingKey);
        $property['state'] = $this->states[$property->StateOrProvince];
        $property['Media'] = json_decode($property->Media);
        $property['CommunityFeatures'] = array_filter(json_decode($property->CommunityFeatures) ?? []);
        $property['MIAMIRE_SubdivisionInformation'] = array_filter(json_decode($property->MIAMIRE_SubdivisionInformation) ?? []);
        $property['StructureType'] = array_filter(json_decode($property->StructureType) ?? []);
        $property['AssociationAmenities'] = array_filter(json_decode($property->AssociationAmenities) ?? []);
        $property['ParkingFeatures'] = array_filter(json_decode($property->ParkingFeatures) ?? []);
        $property['ConstructionMaterials'] = array_filter(json_decode($property->ConstructionMaterials) ?? []);
        $property['LotFeatures'] = array_filter(json_decode($property->LotFeatures) ?? []);
        $property['PatioAndPorchFeatures'] = array_filter(json_decode($property->PatioAndPorchFeatures) ?? []);
        $property['Roof'] = array_filter(json_decode($property->Roof) ?? []);
        $property['Flooring'] = array_filter(json_decode($property->Flooring) ?? []);
        $property['Heating'] = array_filter(json_decode($property->Heating) ?? []);
        $property['InteriorFeatures'] = array_filter(json_decode($property->InteriorFeatures) ?? []);
        $property['Appliances'] = array_filter(json_decode($property->Appliances) ?? []);
        $property['SecurityFeatures'] = array_filter(json_decode($property->SecurityFeatures) ?? []);
        $property['Utilities'] = array_filter(json_decode($property->Utilities) ?? []);
        $property['ArchitecturalStyle'] = array_filter(json_decode($property->ArchitecturalStyle) ?? []);
        $property['Basement'] = array_filter(json_decode($property->Basement) ?? []);
        $property['Longitude'] = (float)($property->Longitude);
        $property['Latitude'] = (float)($property->Latitude);
        $property['cities'] = $cities;
        $property['favorite'] = Auth::check() ? Favorite::where('user_id', Auth::user()->id)->where('property_id', $property->id)->exists() : false;
        $property['agent'] = $property->source == 'web' ? $agent : [];

        return $property;
    }

    public function agents()
    {
        $agents = Agent::get();

        return $this->paginate($agents);
    }

    public function showAgent($id)
    {
        $agent = Agent::where('MemberMlsId', $id)->first();
        $agent['Media'] = json_decode($agent->Media ?? '');

        return $agent;
    }

    public function showRelated($id, $state, $city, $postal)
    {
        $properties = Property::take(3)
                            ->where('StateOrProvince', $state)
                            ->where('City', $city)
                            ->where('PostalCode', $postal)
                            ->where('ListingId', '!=', $id)
                            ->get();

        $relateds = [];
        foreach ($properties as $key => $property) {
            $property['state'] = $this->states[$property->StateOrProvince];
            $property['Media'] = json_decode($property->Media ?? '')[0] ?? [];
            $property['CommunityFeatures'] = json_decode($property->CommunityFeatures);
            $property['MIAMIRE_SubdivisionInformation'] = json_decode($property->MIAMIRE_SubdivisionInformation);
            $property['StructureType'] = json_decode($property->StructureType);
            $property['AssociationAmenities'] = json_decode($property->AssociationAmenities);
            $property['ParkingFeatures'] = json_decode($property->ParkingFeatures);
            $property['ConstructionMaterials'] = json_decode($property->ConstructionMaterials);
            $property['LotFeatures'] = json_decode($property->LotFeatures);
            $property['PatioAndPorchFeatures'] = json_decode($property->PatioAndPorchFeatures);
            $property['Roof'] = json_decode($property->Roof);
            $property['Flooring'] = json_decode($property->Flooring);
            $property['Heating'] = json_decode($property->Heating);
            $property['InteriorFeatures'] = json_decode($property->InteriorFeatures);
            $property['Appliances'] = json_decode($property->Appliances);
            $property['SecurityFeatures'] = json_decode($property->SecurityFeatures);
            $property['Utilities'] = json_decode($property->Utilities);
            $property['ArchitecturalStyle'] = json_decode($property->ArchitecturalStyle);
            $property['Longitude'] = (float)($property->Longitude);
            $property['Latitude'] = (float)($property->Latitude);
            $property['favorite'] = Auth::check() ? Favorite::where('user_id', Auth::user()->id)->where('property_id', $property->id)->exists() : false;

            $relateds[] = $property;
        }

        return $relateds;
    }

    public function openHouse($listingKey)
    {
        return House::where('ListingKey', $listingKey)->first();
    }

    public function newListing($state)
    {
        $states = array_search($state, $this->states);

        if ($states == false) {
            return $this->paginate([], 21);
        }

        $properties = Property::where('StateOrProvince', $states)->latest()->get();

        foreach ($properties as $key => $property) {
            $property['state'] = $this->states[$property->StateOrProvince ?? ''];
            $property['Media'] = json_decode($property->Media ?? '')[0] ?? [];
            $property['CommunityFeatures'] = json_decode($property->CommunityFeatures);
            $property['MIAMIRE_SubdivisionInformation'] = json_decode($property->MIAMIRE_SubdivisionInformation);
            $property['StructureType'] = json_decode($property->StructureType);
            $property['AssociationAmenities'] = json_decode($property->AssociationAmenities);
            $property['ParkingFeatures'] = json_decode($property->ParkingFeatures);
            $property['ConstructionMaterials'] = json_decode($property->ConstructionMaterials);
            $property['LotFeatures'] = json_decode($property->LotFeatures);
            $property['PatioAndPorchFeatures'] = json_decode($property->PatioAndPorchFeatures);
            $property['Roof'] = json_decode($property->Roof);
            $property['Flooring'] = json_decode($property->Flooring);
            $property['Heating'] = json_decode($property->Heating);
            $property['InteriorFeatures'] = json_decode($property->InteriorFeatures);
            $property['Appliances'] = json_decode($property->Appliances);
            $property['SecurityFeatures'] = json_decode($property->SecurityFeatures);
            $property['Utilities'] = json_decode($property->Utilities);
            $property['ArchitecturalStyle'] = json_decode($property->ArchitecturalStyle);
            $property['favorite'] = Auth::check() ? Favorite::where('user_id', Auth::user()->id)->where('property_id', $property->id)->exists() : false;
        }

        return $this->paginate($properties, 21);

    }

    public function getOpenHouse($city)
    {

    }

    public function showListingByState($state)
    {
        $state = str_replace('-', ' ', $state);
        $states = array_search($state, $this->states);

        $stateSetting = State::where('name', 'like', $state)->first();

        $cities = Property::where('StateOrProvince', $states)
                        ->take(21)
                        ->selectRaw('AVG(ListPrice) average, City, count(City) as total')
                        ->groupBy('City')
                        ->orderBy('City')
                        ->get();

        foreach ($cities as $key => $val) {
            if ($val['City'] === null) {
                unset($cities[$key]);
            }
        }

        $array = [];
        $array['cities'] = $cities;
        $array['listing'] = $this->newListing($state);
        $array['setting'] = !empty($stateSetting) ? $stateSetting : null;

        return $array;
    }

    public function showListingByCity(Request $request, $city)
    {
        $checkCity = City::where('name', $city)->exists();

        if (!$checkCity) {
            $city = str_replace('--', ',', $city);
            $city = str_replace('-', ' ', $city);
            $city = str_replace(',', ' - ', $city);
        }

        $citySetting = City::where('name', 'like', $city)->first();

        $bed = $request->bed;
        $bath = $request->bath;
        $type = $request->type;
        $minprice = (int)$request->minprice;
        $maxprice = (int)$request->maxprice;
        $haspool = $request->pool;
        $nearbyschool = $request->school;

        if ($request->has('price') && $request->price != null) {
            if ($request->price == 'asc') {
                $listings = Property::where('City', $city)
                                    ->with('openhouse')
                                    ->orderBy('ListPrice', 'asc');
            } else {
                $listings = Property::where('City', $city)
                                    ->with('openhouse')
                                    ->orderBy('ListPrice', 'desc');
            }
        } elseif ($request->has('date') && $request->date != null) {
            $listings = Property::where('City', $city)
                                ->with('openhouse')
                                ->orderBy('updated_at', 'desc');
        } elseif ($request->has('size') && $request->size != null) {
            $listings = Property::where('City', $city)
                                ->with('openhouse')
                                ->orderBy('LivingArea', 'desc');
        } else {
            $listings = Property::where('City', $city)
                            ->orderBy('updated_at', 'desc')->with('openhouse');
        }

        if ($bed != null) {
            $listings->where('BedroomsTotal', '>=', $bed);
        }

        if ($bath != null) {
            $listings->where('BathroomsTotalDecimal', '>=', $bath);
        }

        if ($type != null) {
            $listings->where('PropertyType', $type);
        }

        if ($minprice != null && $maxprice != null) {
            $listings->whereBetween('ListPrice', [$minprice, $maxprice]);
        }

        if ($nearbyschool == 'Yes') {
            $listings->where(function($query) {
             return $query
                    ->where('ElementarySchool', '!=', null)
                    ->orWhere('HighSchool', '!=', null)
                    ->orWhere('MiddleOrJuniorSchool', '!=', null);
            });
        }

        if ($haspool == 'Yes') {
            $listings->where('MIAMIRE_PoolYN', 1);
        }

        $results = $listings->get();

        foreach ($results as $key => $listing) {
            $listing['state'] = $this->states[$listing->StateOrProvince];
            $listing['Media'] = json_decode($listing->Media ?? '')[0] ?? [];
        }

        $array = [];
        $array['listing'] = $this->paginate($results);
        $array['setting'] = !empty($citySetting) ? $citySetting : null;

        return $array;
    }

    /**
     * Favorite a particular property
     *
     */
    public function favoriteProperty($id)
    {
        $house = Property::where('ListingId', $id)->first();

        return Auth::user()->favorites()->attach($house->id);
    }

    /**
     * Unfavorite a particular property
     *
     */
    public function unFavoriteProperty($id)
    {
        $house = Property::where('ListingId', $id)->first();

        return Auth::user()->favorites()->detach($house->id);
    }

    /**
     * Get all favorite property by user
     *
     */
    public function myFavorites()
    {
        $favorites = Auth::user()->favorites;

        $arrayFavorite = [];
        foreach ($favorites as $property) {
            $property['state'] = $this->states[$property->StateOrProvince];
            $property['Media'] = json_decode($property->Media ?? '')[0] ?? [];
            $property['CommunityFeatures'] = json_decode($property->CommunityFeatures);
            $property['MIAMIRE_SubdivisionInformation'] = json_decode($property->MIAMIRE_SubdivisionInformation);
            $property['StructureType'] = json_decode($property->StructureType);
            $property['AssociationAmenities'] = json_decode($property->AssociationAmenities);
            $property['ParkingFeatures'] = json_decode($property->ParkingFeatures);
            $property['ConstructionMaterials'] = json_decode($property->ConstructionMaterials);
            $property['LotFeatures'] = json_decode($property->LotFeatures);
            $property['PatioAndPorchFeatures'] = json_decode($property->PatioAndPorchFeatures);
            $property['Roof'] = json_decode($property->Roof);
            $property['Flooring'] = json_decode($property->Flooring);
            $property['Heating'] = json_decode($property->Heating);
            $property['InteriorFeatures'] = json_decode($property->InteriorFeatures);
            $property['Appliances'] = json_decode($property->Appliances);
            $property['SecurityFeatures'] = json_decode($property->SecurityFeatures);
            $property['Utilities'] = json_decode($property->Utilities);
            $property['ArchitecturalStyle'] = json_decode($property->ArchitecturalStyle);
            $property['Longitude'] = (float)($property->Longitude);
            $property['Latitude'] = (float)($property->Latitude);
            $property['favorite'] = Auth::check() ? Favorite::where('user_id', Auth::user()->id)->where('property_id', $property->id)->exists() : false;

            $arrayFavorite[] = $property;
        }

        return $arrayFavorite;
    }

    public function alerts()
    {
        $user = Auth::user();

        return AlertUser::where('user_id', $user->id)->get();
    }

    public function addAlert(Request $request)
    {
        $user = Auth::user();

        $inputs = $request->all();
        $inputs->user_id = $user->id;
        $inputs->features = json_encode($request->features);

        $created = AlertUser::create($inputs);

        if ($created) {
            return response()->json(['message' => 'Alert set in ' . $alert->city]);
        }
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $states = array_search($search, $this->states);

        $results = [];
        $results['city'] = [];

        $property = Property::select('City', 'StateOrProvince')
                            ->where('City', 'LIKE', '%'.$search.'%')
                            ->groupBy('City', 'StateOrProvince')
                            ->get();

        if (!empty($property)) {
            foreach ($property as $prop) {
                $prop['state'] = $this->states[$prop->StateOrProvince];
            }   

            $results['city'] = $property;
        }

        $properties = Property::select('id','UnparsedAddress', 'PropertyType', 'ListingId', 'StateOrProvince', 'City', 'PostalCode')
                                ->where('UnparsedAddress', 'LIKE', '%'.$search.'%')
                                ->orderBy('ModificationTimestamp', 'desc')
                                ->limit(30)
                                ->get();

        if (!empty($properties)) {
            foreach ($properties as $property) {
                $property['state'] = $this->states[$property->StateOrProvince];
            }   

            $results['address'] = $properties;
        }

        $pages = Page::select('id','title', 'slug', 'type')
                    ->where('markdown', 'LIKE','%'.$search.'%')
                    ->orWhere('title', 'LIKE','%'.$search.'%')
                    ->orderBy('updated_at', 'desc')
                    ->get();

        if (!empty($pages)) {
            $results['blog'] = $pages;
        }

        return $results;
    }

    public function addAgent(Request $request)
    {
        $this->validate($request, [
            'MemberFirstName' => 'required',
            'MemberLastName' => 'required',
            'MemberEmail' => 'required|email:filter|max:255|unique:agents'
        ]);

        $inputs = $request->all();
        $inputs['MemberFullName'] = $request->MemberFirstName . ' ' . $request->MemberLastName;

        if (Auth::user()->role != 'user') {
            $user = Agent::create($inputs);

            return response()->json(['message' => 'Agent created.']);
        }
    }

    public function getCities()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $response = Http::acceptJson()->get('https://ipinfo.io/' . $ip . '/json?token=10f9001a70d669');
        $res = json_decode($response->body(), true);
        $region = $res['region'] ?? 'Florida';

        $cities = Property::where('StateOrProvince', $region == 'New York' || $region == 'New Jersey' ? 'NY' : 'FL')
                        ->selectRaw('AVG(ListPrice) average, City, count(City) as total')
                        ->groupBy('City')
                        ->orderBy('City')
                        ->get();

        foreach ($cities as $key => $val) {
            if ($val['City'] === null) {
                unset($cities[$key]);
            }
        }
        return $cities;
    }

    public function sidebar()
    {
        $cities = Property::where('StateOrProvince', 'FL')
                        ->selectRaw('AVG(ListPrice) average, City, count(City) as total')
                        ->groupBy('City')
                        ->take(10)
                        ->get();

        foreach ($cities as $key => $val) {
            if ($val['City'] === null) {
                unset($cities[$key]);
            }
        }

        $pages = Page::where('type', 'blog')->take(5)->latest()->get();
        $agents = WebAgent::take(6)->get();
        
        $array = [];
        $array['cities'] = $cities;
        $array['blogs'] = $pages;
        $array['agents'] = $agents;

        return $array;
    }

    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function bing($property)
    {
        $siteurl = 'https://anshell.com';
        $state = $siteurl . '/Florida';
        $city = $property->City != null ? preg_replace('/[[:space:]]+/', '-', $property->City) : '';
        $mls = $property->ListingId != null ? $property->ListingId : '';
        $address = $property->UnparsedAddress != null ? preg_replace('/[^A-Za-z0-9-]+/', '-', substr($property->UnparsedAddress, 0, strpos($property->UnparsedAddress, ","))) : '';
        $type = $property->PropertyType != null ? preg_replace('/[[:space:]]+/', '-', $property->PropertyType) : '';
        
        $slug =  $state . '/' . $city . '/' . 'MLS-' . $mls . '-' . $address . '/' . $type;

        $links = [];
        $links[] = $slug;

        $data = new stdClass();
        $data->siteUrl = "https://anshell.com";
        $data->urlList = $links;

        $curl = curl_init();
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
