<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\House;
use Illuminate\Support\Facades\Http;

class OpenHouseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:openhouse';
    protected $access_token = 'fb63dafcbaa68161fa24ccecc7bd6197';
    protected $api = 'https://api.bridgedataoutput.com/api/v2/miamire/';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get open house from API';

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
        $listings = $this->connect('openhouses', '', 100);

        foreach ($listings as $key => $listing) {
            $exist = House::where('OpenHouseKey', $listing['OpenHouseKey'])->exists();
            if (!$exist) {
                $house = new House();
                $house->OpenHouseKey = $listing['OpenHouseKey'];
                $house->SourceSystemKey = $listing['SourceSystemKey'];
                $house->BridgeModificationTimestamp = $listing['BridgeModificationTimestamp'];
                $house->OriginatingSystemName = $listing['OriginatingSystemName'];
                $house->OpenHouseStartTime = $listing['OpenHouseStartTime'];
                $house->OpenHouseEndTime = $listing['OpenHouseEndTime'];
                $house->OpenHouseId = $listing['OpenHouseId'];
                $house->VirtualOpenHouseURL = $listing['VirtualOpenHouseURL'];
                $house->ListingId = $listing['ListingId'];
                $house->OpenHouseDate = $listing['OpenHouseDate'];
                $house->ModificationTimestamp = $listing['ModificationTimestamp'];
                $house->ListingKey = $listing['ListingKey'];
                $house->ListingKeyNumeric = $listing['ListingKeyNumeric'];
                $house->OpenHouseRemarks = $listing['OpenHouseRemarks'];

                $house->save();
            }
        }

        $this->info('Open house has been saved to database');
        logger('Open house has been saved to database');
    }

    public function connect($resource, $fields = '', $limit = 10)
    {
        $response = Http::acceptJson()->get($this->api . $resource, [
            'access_token'  => $this->access_token,
            'sortBy'        => 'ModificationTimestamp',
            'limit'         => $limit,
            'fields'        => $fields
        ]);

        return $response['bundle'];
    }
}
