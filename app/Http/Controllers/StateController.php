<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\State;
use App\Models\City;
use App\Models\Property;
use App\Models\Page;
use App\Models\WebAgent;
use App\Models\Favorite;
use Auth;

use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class StateController extends Controller
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

    public function states()
    {
        return State::get();
    }

    public function showState($name)
    {
        $name = str_replace('--', ',', $name);
        $name = str_replace('-', ' ', $name);
        $name = str_replace(',', ' - ', $name);

        $state = State::where('name', $name)->first();

        if (!empty($state)) {
            return $state;
        }

        return false;
    }

    public function cities()
    {
        return City::where('name', '!=', null)->orderBy('name')->get();
    }

    public function showCity($name)
    {
        $name = str_replace('--', ',', $name);
        $name = str_replace('-', ' ', $name);
        $name = str_replace(',', ' - ', $name);

        $city = City::where('name', $name)->first();

        if (!empty($city)) {
            return $city;
        }

        return false;
    }

    public function saveState(Request $request, $name)
    {
        $state = State::where('name', $name)->first();

        $inputs = $request->all();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file = $image->store('images/state', 'public');
 
            $inputs['image'] = $file;
        } else {
            $inputs['image'] = $request->image == null ? $state->getRawOriginal('image') : null;
        }

        if (!empty($state)) {
            $saved = State::where('name', $name)->update($inputs);
            if ($saved) {
                return response()->json(['status' => true, 'message' => 'Updated successfully.']);
            }
        }
    }

    public function saveCity(Request $request, $name)
    {
        $city = City::where('name', $name)->first();

        $inputs = $request->all();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file = $image->store('images/city', 'public');
 
            $inputs['image'] = $file;
        } else {
            $inputs['image'] = $request->image == null ? $city->getRawOriginal('image') : null;
        }

        if (!empty($city)) {
            $saved = City::where('name', $name)->update($inputs);
            if ($saved) {
                return response()->json(['status' => true, 'message' => 'Updated successfully.']);
            }
        }
    }

    public function search($query)
    {
        $results = [];
        $results['listing'] = [];
        $results['pages'] = [];
        $results['agents'] = [];

        $properties = Property::where('City', 'LIKE','%'.$query.'%')
                        ->orWhere('PostalCode', $query)
                        ->orWhere('UnparsedAddress', 'LIKE','%'.$query.'%')
                        ->orWhere('StreetName', 'LIKE','%'.$query.'%')
                        ->orWhere('MiddleOrJuniorSchool', 'LIKE','%'.$query.'%')
                        ->orWhere('ElementarySchool', 'LIKE','%'.$query.'%')
                        ->orWhere('HighSchool', 'LIKE','%'.$query.'%')
                        ->orWhere('PropertyType', $query)
                        ->orderBy('updated_at', 'desc')
                        ->paginate(21);

        foreach ($properties as $property) {
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

        $results['listing'][] = $properties;

        $pages = Page::where('markdown', 'LIKE','%'.$query.'%')
                    ->orWhere('title', 'LIKE','%'.$query.'%')
                    ->orderBy('updated_at', 'desc')
                    ->get();

        foreach ($pages as $page) {
           $results['pages'][] = $page;
        }

        $agents = WebAgent::where('name', $query)
                    ->orWhere('firstname', $query)
                    ->orWhere('lastname', $query)
                    ->orderBy('updated_at', 'desc')
                    ->get();

        foreach ($agents as $agent) {
           $results['agents'][] = $agent;
        }

        return $results;
    }

    public function advancedSearch(Request $request)
    {
        $city = $request->city;
        $state = $request->state;
        $street = $request->street;
        $type = $request->type;
        $zip = $request->zip;
        $bed = $request->bed;
        $bath = $request->bath;
        $minprice = (int)$request->min_price;
        $maxprice = (int)$request->max_price;
        $year_built = (int)$request->year_built;
        $nearby_school = $request->nearby_school;
        $pool = $request->pool;
        $basement = $request->basement;
        $min_sqf = $request->min_sqf;
        $exclude = $request->exclude;

        $checkCity = City::where('name', $city)->exists();

        if ($checkCity) {
            $listings = Property::where('City', $city)->where('StateOrProvince', $state);

            if ($street != null) {
                $listings->where('StreetName', 'LIKE', '%'.$street.'%');
            }

            if ($type != 'Any') {
                $listings->where('PropertyType', $type);
            }

            if ($zip != null) {
                $listings->where('PostalCode', $zip);
            }

            if ($bed != 'Any') {
                $listings->where('BedroomsTotal', '>=', $bed);
            }

            if ($bath != 'Any') {
                $listings->where('BathroomsTotalDecimal', '>=', $bath);
            }

            if ($minprice != null && $maxprice != null) {
                $listings->whereBetween('ListPrice', [$minprice, $maxprice]);
            }

            if ($year_built != null) {
                $listings->where('YearBuilt', $year_built);
            }

            if ($nearby_school == 'Yes') {
                $listings->where(function($query) {
                return $query
                        ->where('ElementarySchool', '!=', null)
                        ->orWhere('HighSchool', '!=', null)
                        ->orWhere('MiddleOrJuniorSchool', '!=', null);
                });
            }

            if ($pool == 'Yes') {
                $listings->where('MIAMIRE_PoolYN', 1);
            }

            if ($basement == 'Yes') {
                $listings->where('Basement', 1);
            }

            if ($min_sqf != null) {
                $listings->where('LivingArea', '>=', $min_sqf);
            }

            if ($exclude) {
                $listings->where('StandardStatus', '!=', 'Active Under Contract');
            }

            if ($request->has('price') && $request->price != null) {
                if ($request->price == 'asc') {
                    $listings->where('ListPrice', '!=', null)->orderBy('ListPrice', 'asc');
                } else {
                    $listings->where('ListPrice', '!=', null)->orderBy('ListPrice', 'desc');
                }
            } elseif ($request->has('date') && $request->date != null) {
                $listings->orderBy('updated_at', 'desc');
            } elseif ($request->has('size') && $request->size != null) {
                $listings->where('LivingArea', '!=', null)->orderBy('LivingArea', 'desc');
            } else {
                $listings->orderBy('updated_at', 'desc');
            }

            $results = $listings->get();

            foreach ($results as $key => $listing) {
                $listing['state'] = $this->states[$listing->StateOrProvince];
                $listing['Media'] = json_decode($listing->Media ?? '')[0] ?? [];
            }

            return $this->paginate($results);
        } else {
            return $this->paginate([]);
        }
        
    }

    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
