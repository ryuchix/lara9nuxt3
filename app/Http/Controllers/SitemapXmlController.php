<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WebAgent;
use App\Models\Property;
use App\Models\City;
use App\Models\State;
use App\Models\Page;
use App;

class SitemapXmlController extends Controller
{
    public function index() {
        return response()->view('sitemap')->header('Content-Type', 'text/xml');
    }

    public function agents() {
        $agents = WebAgent::get();
        $siteurl = 'https://anshell.com';

        return response()->view('sitemap-agents', [
            'agents' => $agents,
            'siteurl' => $siteurl
        ])->header('Content-Type', 'text/xml');
    }

    public function properties() {
        // create new sitemap object
        $sitemap = App::make('sitemap');

        // get all products from db (or wherever you store them)
        $products = Property::orderBy('created_at', 'desc')->get();

        // counters
        $counter = 0;
        $sitemapCounter = 0;

        // add every product to multiple sitemaps with one sitemap index
        foreach ($products as $p) {
            if ($counter == 50000) {
                // generate new sitemap file
                $sitemap->store('xml', 'sitemap-' . $sitemapCounter, 'uploads');
                // add the file to the sitemaps array
                $sitemap->addSitemap(secure_url('uploads/sitemap-' . $sitemapCounter . '.xml'));
                // reset items array (clear memory)
                $sitemap->model->resetItems();
                // reset the counter
                $counter = 0;
                // count generated sitemap
                $sitemapCounter++;
            }

            // add product to items array
            $siteurl = 'https://anshell.com';
            $state = $siteurl . '/Florida';
            $city = $p->City != null ? preg_replace('/[[:space:]]+/', '-', $p->City) : '';
            $mls = $p->ListingId != null ? $p->ListingId : '';
            $address = $p->UnparsedAddress != null ? preg_replace('/[^A-Za-z0-9-]+/', '-', substr($p->UnparsedAddress, 0, strpos($p->UnparsedAddress, ","))) : '';
            $type = $p->PropertyType != null ? preg_replace('/[[:space:]]+/', '-', $p->PropertyType) : '';
            
            $slug =  $state . '/' . $city . '/' . 'MLS-' . $mls . '-' . $address . '/' . $type;
            
            $sitemap->add($slug, $p->created_at, 'weekly', '0.5');
            // count number of elements
            $counter++;
        }

        // you need to check for unused items
        if (!empty($sitemap->model->getItems())) {
            // generate sitemap with last items
            $sitemap->store('xml', 'sitemap-' . $sitemapCounter, 'uploads');
            // add sitemap to sitemaps array
            $sitemap->addSitemap(secure_url('uploads/sitemap-' . $sitemapCounter . '.xml'));
            // reset items array
            $sitemap->model->resetItems();
        }

        // generate new sitemapindex that will contain all generated sitemaps above
        $sitemap->store('sitemapindex', 'sitemap-properties', 'uploads');

        return redirect('uploads/sitemap-properties.xml');
    }

    public function states() {
        $states = State::get();
        $siteurl = 'https://anshell.com';

        return response()->view('sitemap-states', [
            'states' => $states,
            'siteurl' => $siteurl
        ])->header('Content-Type', 'text/xml');
    }

    public function cities() {
        $cities = City::get();
        $siteurl = 'https://anshell.com';

        return response()->view('sitemap-cities', [
            'cities' => $cities,
            'siteurl' => $siteurl
        ])->header('Content-Type', 'text/xml');
    }

    public function pages() {
        $pages = Page::where('status', 'true')->get();
        $siteurl = 'https://anshell.com';

        return response()->view('sitemap-pages', [
            'pages' => $pages,
            'siteurl' => $siteurl
        ])->header('Content-Type', 'text/xml');
    }
}
