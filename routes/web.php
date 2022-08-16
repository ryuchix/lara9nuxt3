<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SitemapXmlController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/run-property', function () {
    \Artisan::call('run:property');
});

Route::get('/sitemap.xml', [SitemapXmlController::class, 'index']);

Route::get('/sitemap-pages.xml', [SitemapXmlController::class, 'pages']);
Route::get('/sitemap-agents.xml', [SitemapXmlController::class, 'agents']);
Route::get('/sitemap-cities.xml', [SitemapXmlController::class, 'cities']);
Route::get('/sitemap-states.xml', [SitemapXmlController::class, 'states']);
Route::get('/sitemap-properties.xml', [SitemapXmlController::class, 'properties']);

Route::get('/sitemap_index.xml', [SitemapXmlController::class, 'index']);

Route::get('/iptest', function() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    $response = Http::acceptJson()->get('https://ipinfo.io/'.$ip.'/json?token=10f9001a70d669');
    $res = json_decode($response->body(), true);
    echo $res['region'] ?? '';
});