<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Agent;
use Illuminate\Support\Facades\Http;

class AgentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:agent';
    protected $access_token = 'fb63dafcbaa68161fa24ccecc7bd6197';
    protected $api = 'https://api.bridgedataoutput.com/api/v2/miamire/';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get agent from API';

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
            $listings = $this->connect('agents', '', 200);

            foreach ($listings as $key => $listing) {
                $exist = Agent::where('MemberMlsId', $listing['MemberMlsId'])->exists();
                if (!$exist) {
    
                    $agent = new Agent();
                    $agent->MemberAOR = $listing['MemberAOR'];
                    $agent->MemberCity = $listing['MemberCity'];
                    $agent->MIAMIRE_MUC = $listing['MIAMIRE_MUC'];
                    $agent->MemberEmail = $listing['MemberEmail'];
                    $agent->MemberMlsId = $listing['MemberMlsId'];
                    $agent->MemberStatus = $listing['MemberStatus'];
                    $agent->MemberCountry = $listing['MemberCountry'];
                    $agent->MemberAddress1 = $listing['MemberAddress1'];
                    $agent->MemberAddress2 = $listing['MemberAddress2'];
                    $agent->MemberFullName = $listing['MemberFullName'];
                    $agent->MemberLastName = $listing['MemberLastName'];
                    $agent->MemberNickname = $listing['MemberNickname'];
                    $agent->MemberFirstName = $listing['MemberFirstName'];
                    $agent->MemberHomePhone = $listing['MemberHomePhone'];
                    $agent->SocialMediaType = $listing['SocialMediaType'];
                    $agent->MemberNameSuffix = $listing['MemberNameSuffix'];
                    $agent->MemberMiddleName = $listing['MemberMiddleName'];
                    $agent->MemberPostalCode = $listing['MemberPostalCode'];
                    $agent->MemberDirectPhone = $listing['MemberDirectPhone'];
                    $agent->MemberMobilePhone = $listing['MemberMobilePhone'];
                    $agent->MemberStateLicense = $listing['MemberStateLicense'];
                    $agent->MemberPreferredPhone = $listing['MemberPreferredPhone'];
                    $agent->MemberStateOrProvince = $listing['MemberStateOrProvince'];
                    $agent->ModificationTimestamp = $listing['ModificationTimestamp'];
                    $agent->Media = json_encode($listing['Media']);
                    $agent->JobTitle = $listing['JobTitle'];
                    $agent->OfficeName = $listing['OfficeName'];

                    $agent->save();
                }
            }

            $this->info('Agent has been saved to database');
            logger('Agent has been saved to database');
        } catch (\Throwable $th) {
            logger(json_encode($th));
        }
    }

    public function connect($resource, $fields = '', $limit = 10)
    {
        try {
            $response = Http::acceptJson()->get($this->api . $resource, [
                'access_token'  => $this->access_token,
                'sortBy'        => 'ModificationTimestamp',
                'limit'         => $limit,
                'fields'        => $fields,
                // 'offset'        => 1959
            ]);

            return $response['bundle'];
        } catch (\Throwable $th) {
            logger(json_encode($th));
        }
    }
}
