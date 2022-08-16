<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    use HasFactory;

    protected $fillable = [

        'website_name',
        'website_description',
        'website_keywords',
        'website_logo',
        'website_image',

        'schema_person_name',
        'schema_person_firstname',
        'schema_person_lastname',
        'schema_person_description',
        'schema_person_image',
        'schema_person_websites',

        'schema_business_name',
        'schema_business_image',
        'schema_business_description',
        'schema_business_street',
        'schema_business_post_office',
        'schema_business_locality',
        'schema_business_region',
        'schema_business_postal',
        'schema_business_country',
        'schema_business_latitude',
        'schema_business_longitude',
        'schema_business_google_map',

        'sell_title',
        'sell_description',
        'sell_keywords',
        'sell_image',

        'agents_title',
        'agents_description',
        'agents_keywords',
        'agents_image',

        'listing_state_title',
        'listing_state_description',
        'listing_state_keywords',
        'listing_state_image',

        'listing_city_title',
        'listing_city_description',
        'listing_city_keywords',
        'listing_city_image',

        'property_title',
        'property_description',
        'property_keywords',

        'contact_us_title',
        'contact_us_description',
        'contact_us_keywords',
        'contact_us_image',
    ];

    public function getWebsiteLogoAttribute($value)
    {
        return $value != null ? asset('uploads/'.$value) : null;
    }

    public function getWebsiteImageAttribute($value)
    {
        return $value != null ? asset('uploads/'.$value) : null;
    }

    public function getSchemaPersonImageAttribute($value)
    {
        return $value != null ? asset('uploads/'.$value) : null;
    }

    public function getSchemaBusinessImageAttribute($value)
    {
        return $value != null ? asset('uploads/'.$value) : null;
    }

    public function getSellImageAttribute($value)
    {
        return $value != null ? asset('uploads/'.$value) : null;
    }

    public function getAgentsImageAttribute($value)
    {
        return $value != null ? asset('uploads/'.$value) : null;
    }

    public function getListingStateImageAttribute($value)
    {
        return $value != null ? asset('uploads/'.$value) : null;
    }

    public function getListingCityImageAttribute($value)
    {
        return $value != null ? asset('uploads/'.$value) : null;
    }

    public function getContactUsImageAttribute($value)
    {
        return $value != null ? asset('uploads/'.$value) : null;
    }

    // public function getSchemaPersonWebsitesAttribute($value)
    // {
    //     return $value != null ? explode(",", $value) : [];
    // }
}
