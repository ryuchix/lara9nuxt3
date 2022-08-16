<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'MemberAOR',
        'MemberCity',
        'MIAMIRE_MUC',
        'MemberEmail',
        'MemberMlsId',
        'MemberStatus',
        'MemberCountry',
        'MemberAddress1',
        'MemberAddress2',
        'MemberFullName',
        'MemberLastName',
        'MemberNickname',
        'MemberFirstName',
        'MemberHomePhone',
        'SocialMediaType',
        'MemberNameSuffix',
        'MemberMiddleName',
        'MemberPostalCode',
        'MemberDirectPhone',
        'MemberMobilePhone',
        'MemberStateLicense',
        'MemberPreferredPhone',
        'MemberStateOrProvince',
        'ModificationTimestamp',
        'Media',
        'JobTitle',
        'OfficeName'
    ];

}