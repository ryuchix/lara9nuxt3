<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Property;

class House extends Model
{
    use HasFactory;

    protected $fillable = [
        'OpenHouseKey',
        'SourceSystemKey',
        'BridgeModificationTimestamp',
        'OriginatingSystemName',
        'OpenHouseStartTime',
        'OpenHouseEndTime',
        'OpenHouseId',
        'VirtualOpenHouseURL',
        'ListingId',
        'OpenHouseDate',
        'ModificationTimestamp',
        'ListingKey',
        'ListingKeyNumeric',
        'OpenHouseRemarks'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'ListingKey', 'ListingKey','id');
    }

    
}
