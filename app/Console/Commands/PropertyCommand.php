<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;
use App\Models\City;
use Illuminate\Support\Facades\Http;
use \stdClass;

class PropertyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:property';
    protected $simplefields = 'Coordinates,StandardStatus,ListingKey,Media,UnparsedAddress,BedroomsTotal,BathroomsTotalDecimal,ListPrice,PropertyType,ListingId,StateOrProvince,City,PostalCode,ModificationTimestamp,ListAgentMlsId,MajorChangeTimestamp,LivingArea,Latitude,Longitude,ListAgentStateLicense,StreetName,ExpirationDate,SyndicationRemarks,ListAOR,PublicRemarks,AvailabilityDate,CommunityFeatures,MIAMIRE_SubdivisionInformation,Furnished,StructureType,AssociationAmenities,HighSchool,MiddleOrJuniorSchool,ElementarySchool,AssociationFee,CountyOrParish,ParkingFeatures,ConstructionMaterials,LotFeatures,Roof,PatioAndPorchFeatures,FireplaceYN,Flooring,Heating,InteriorFeatures,Appliances,SecurityFeatures,PropertySubType,Utilities,ArchitecturalStyle,YearBuiltDetails,PatioAndPorchFeatures,MIAMIRE_PoolYN,MIAMIRE_RATIO_CurrentPrice_By_SQFT,YearBuilt';
    protected $access_token = 'fb63dafcbaa68161fa24ccecc7bd6197';
    protected $api = 'https://api.bridgedataoutput.com/api/v2/miamire/';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get property from API';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $listings = $this->connect('listings', $this->simplefields, 200);

            $dups = [];
            $nodata = [];
            foreach ($listings as $key => $listing) {
                if ($listing['City'] == null || $listing['UnparsedAddress'] == null || $listing['StateOrProvince'] == null || $listing['ListingId'] == null) {
                    $nodata[] = 'nodata';
                    continue;
                }

                $exist = Property::where('ListingId', $listing['ListingId'])->exists();
                if (!$exist) {
                    $property = new Property();
                    $property->ListingKey = $listing['ListingKey'];
                    $property->Media = json_encode($listing['Media']);
                    $property->UnparsedAddress = $listing['UnparsedAddress'];
                    $property->BedroomsTotal = $listing['BedroomsTotal'];
                    $property->BathroomsTotalDecimal = $listing['BathroomsTotalDecimal'];
                    $property->ListPrice = $listing['ListPrice'];
                    $property->PropertyType = $listing['PropertyType'];
                    $property->ListingId = $listing['ListingId'];
                    $property->StateOrProvince = $listing['StateOrProvince'];
                    $property->City = $listing['City'];
                    $property->StandardStatus = $listing['StandardStatus'];
                    $property->PostalCode = $listing['PostalCode'];
                    $property->ModificationTimestamp = $listing['ModificationTimestamp'];
                    $property->ListAgentMlsId = $listing['ListAgentMlsId'];
                    $property->MajorChangeTimestamp = $listing['MajorChangeTimestamp'];
                    $property->LivingArea = $listing['LivingArea'];
                    $property->Latitude = $listing['Coordinates'][1] ?? NULL;
                    $property->Longitude = $listing['Coordinates'][0] ?? NULL;
                    $property->ListAgentStateLicense = $listing['ListAgentStateLicense'];
                    $property->StreetName = $listing['StreetName'];
                    $property->ExpirationDate = $listing['ExpirationDate'];
                    $property->SyndicationRemarks = $listing['SyndicationRemarks'];
                    $property->ListAOR = $listing['ListAOR'];
                    $property->PublicRemarks = $listing['PublicRemarks'];
                    $property->AvailabilityDate = $listing['AvailabilityDate'];
                    $property->CommunityFeatures = json_encode($listing['CommunityFeatures']);
                    $property->MIAMIRE_SubdivisionInformation = json_encode($listing['MIAMIRE_SubdivisionInformation']);
                    $property->Furnished = $listing['Furnished'];
                    $property->StructureType = json_encode($listing['StructureType']);
                    $property->AssociationAmenities = json_encode($listing['AssociationAmenities']);
                    $property->HighSchool = $listing['HighSchool'];
                    $property->MiddleOrJuniorSchool = $listing['MiddleOrJuniorSchool'];
                    $property->ElementarySchool = $listing['ElementarySchool'];
                    $property->AssociationFee = $listing['AssociationFee'];
                    $property->ParkingFeatures = json_encode($listing['ParkingFeatures']);
                    $property->ConstructionMaterials = json_encode($listing['ConstructionMaterials']);
                    $property->LotFeatures = json_encode($listing['LotFeatures']);
                    $property->Roof = json_encode($listing['Roof']);
                    $property->PatioAndPorchFeatures = json_encode($listing['PatioAndPorchFeatures']);
                    $property->FireplaceYN = $listing['FireplaceYN'];
                    $property->Flooring = json_encode($listing['Flooring']);
                    $property->Heating = json_encode($listing['Heating']);
                    $property->InteriorFeatures = json_encode($listing['InteriorFeatures']);
                    $property->Appliances = json_encode($listing['Appliances']);
                    $property->SecurityFeatures = json_encode($listing['SecurityFeatures']);
                    $property->PropertySubType = $listing['PropertySubType'];
                    $property->Utilities = json_encode($listing['Utilities']);
                    $property->ArchitecturalStyle = json_encode($listing['ArchitecturalStyle']);
                    $property->YearBuiltDetails = $listing['YearBuiltDetails'];
                    $property->MIAMIRE_PoolYN = $listing['MIAMIRE_PoolYN'];
                    $property->MIAMIRE_RATIO_CurrentPrice_By_SQFT = $listing['MIAMIRE_RATIO_CurrentPrice_By_SQFT'];
                    $property->YearBuilt = $listing['YearBuilt'];
                    
                    $property->save();

                    $this->bing($property);

                } else {
                    $dups[] = $listing['ListingId'];

                    $property = Property::where('ListingId', $listing['ListingId'])->first();
                    $property->ListingKey = $listing['ListingKey'];
                    $property->Media = json_encode($listing['Media']);
                    $property->UnparsedAddress = $listing['UnparsedAddress'];
                    $property->BedroomsTotal = $listing['BedroomsTotal'];
                    $property->BathroomsTotalDecimal = $listing['BathroomsTotalDecimal'];
                    $property->ListPrice = $listing['ListPrice'];
                    $property->PropertyType = $listing['PropertyType'];
                    $property->ListingId = $listing['ListingId'];
                    $property->StateOrProvince = $listing['StateOrProvince'];
                    $property->City = $listing['City'];
                    $property->StandardStatus = $listing['StandardStatus'];
                    $property->PostalCode = $listing['PostalCode'];
                    $property->ModificationTimestamp = $listing['ModificationTimestamp'];
                    $property->ListAgentMlsId = $listing['ListAgentMlsId'];
                    $property->MajorChangeTimestamp = $listing['MajorChangeTimestamp'];
                    $property->LivingArea = $listing['LivingArea'];
                    $property->Latitude = $listing['Coordinates'][1] ?? NULL;
                    $property->Longitude = $listing['Coordinates'][0] ?? NULL;
                    $property->ListAgentStateLicense = $listing['ListAgentStateLicense'];
                    $property->StreetName = $listing['StreetName'];
                    $property->ExpirationDate = $listing['ExpirationDate'];
                    $property->SyndicationRemarks = $listing['SyndicationRemarks'];
                    $property->ListAOR = $listing['ListAOR'];
                    $property->PublicRemarks = $listing['PublicRemarks'];
                    $property->AvailabilityDate = $listing['AvailabilityDate'];
                    $property->CommunityFeatures = json_encode($listing['CommunityFeatures']);
                    $property->MIAMIRE_SubdivisionInformation = json_encode($listing['MIAMIRE_SubdivisionInformation']);
                    $property->Furnished = $listing['Furnished'];
                    $property->StructureType = json_encode($listing['StructureType']);
                    $property->AssociationAmenities = json_encode($listing['AssociationAmenities']);
                    $property->HighSchool = $listing['HighSchool'];
                    $property->MiddleOrJuniorSchool = $listing['MiddleOrJuniorSchool'];
                    $property->ElementarySchool = $listing['ElementarySchool'];
                    $property->AssociationFee = $listing['AssociationFee'];
                    $property->ParkingFeatures = json_encode($listing['ParkingFeatures']);
                    $property->ConstructionMaterials = json_encode($listing['ConstructionMaterials']);
                    $property->LotFeatures = json_encode($listing['LotFeatures']);
                    $property->Roof = json_encode($listing['Roof']);
                    $property->PatioAndPorchFeatures = json_encode($listing['PatioAndPorchFeatures']);
                    $property->FireplaceYN = $listing['FireplaceYN'];
                    $property->Flooring = json_encode($listing['Flooring']);
                    $property->Heating = json_encode($listing['Heating']);
                    $property->InteriorFeatures = json_encode($listing['InteriorFeatures']);
                    $property->Appliances = json_encode($listing['Appliances']);
                    $property->SecurityFeatures = json_encode($listing['SecurityFeatures']);
                    $property->PropertySubType = $listing['PropertySubType'];
                    $property->Utilities = json_encode($listing['Utilities']);
                    $property->ArchitecturalStyle = json_encode($listing['ArchitecturalStyle']);
                    $property->YearBuiltDetails = $listing['YearBuiltDetails'];
                    $property->MIAMIRE_PoolYN = $listing['MIAMIRE_PoolYN'];
                    $property->MIAMIRE_RATIO_CurrentPrice_By_SQFT = $listing['MIAMIRE_RATIO_CurrentPrice_By_SQFT'];
                    $property->YearBuilt = $listing['YearBuilt'];
                    
                    $property->save();

                }
                $exists = City::where('name', $listing['City'])->exists();
                if (!$exists) {
                    City::create([
                        'name' => $listing['City']
                    ]);
                }
            }

            logger('dups are: ' . count($dups));
            logger('no data: ' .count($nodata));

            $response = Http::get('https://www.google.com/ping?sitemap=https://api.anshell.com/sitemap.xml');

            $this->info('Property has been saved to database');
            logger('Property has been saved to database');
        } catch (\Exception $ex) {
            logger($ex->getLine());
        } catch (\Throwable $th) {
            logger(json_encode($th->getMessage()));
        }
    }

    public function connect($resource, $fields = '', $limit = 200)
    {
        $response = Http::acceptJson()->get($this->api . $resource, [
            'access_token'  => $this->access_token,
            'sortBy'        => 'ModificationTimestamp',
            'limit'         => $limit,
            'fields'        => $fields
        ]);

        return $response['bundle'];
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
