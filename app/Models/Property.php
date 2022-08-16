<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\House;
use App\Models\User;
use App\Models\Favorite;
use Auth;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'ListingKey',
        'source',
        'StandardStatus',
        'Media',
        'UnparsedAddress',
        'BedroomsTotal',
        'BathroomsTotalDecimal',
        'ListPrice',
        'PropertyType',
        'ListingId',
        'StateOrProvince',
        'City',
        'PostalCode',
        'ModificationTimestamp',
        'ListAgentMlsId',
        'MajorChangeTimestamp',
        'LivingArea',
        'Latitude',
        'Longitude',
        'ListAgentStateLicense',
        'StreetName',
        'ExpirationDate',
        'SyndicationRemarks',
        'ListAOR',
        'PublicRemarks',
        'AvailabilityDate',
        'CommunityFeatures',
        'MIAMIRE_SubdivisionInformation',
        'Furnished',
        'StructureType',
        'AssociationAmenities',
        'HighSchool',
        'MiddleOrJuniorSchool',
        'ElementarySchool',
        'AssociationFee',
        'CountyOrParish',
        'ParkingFeatures',
        'ConstructionMaterials',
        'LotFeatures',
        'Roof',
        'PatioAndPorchFeatures',
        'FireplaceYN',
        'Flooring',
        'Basement',
        'Heating',
        'InteriorFeatures',
        'Appliances',
        'SecurityFeatures',
        'PropertySubType',
        'Utilities',
        'ArchitecturalStyle',
        'YearBuiltDetails',
        'PatioAndPorchFeatures',
        'MIAMIRE_PoolYN',
        'MIAMIRE_RATIO_CurrentPrice_By_SQFT',
        'YearBuilt'
    ];

    public function openhouse()
    {
        return $this->hasMany(House::class, 'ListingKey', 'ListingKey');
    }

    public function users()
    {
         return $this->belongsToMany(User::class, 'favorites');
    }

    public function usersThatFavorited()
    {
         return $this->belongsToMany(User::class, 'favorites');
    }
}
