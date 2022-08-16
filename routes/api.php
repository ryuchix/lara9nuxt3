<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\PageController;
use App\Http\Auth\SocialLoginController;
use App\Http\Controllers\WebAgentController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\IssueController;
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::get('/', [Controller::class, 'routes'])
//     ->name('entry-point')
//     ->withoutMiddleware('api');
// Route::get('example', [Controller::class, 'example'])->name('example');
// Route::get('error', [Controller::class, 'exampleError'])->name('error');

// // Authentication
// Route::get('login', [Controller::class, 'auth'])->name('login');

// Route::controller(AuthController::class)->group(function () {
//     Route::get('redirect/{provider}', 'redirect')->name('provider.redirect');
//     Route::get('callback/{provider}', 'callback')->name('provider.callback');
//     Route::get('onetap/{credential}', 'onetap')->name('onetap.support');
//     Route::post('attempt', 'attempt')->name('auth.attempt');
//     Route::post('login', 'login')->name('auth.login');
//     Route::get('logout', 'logout')->middleware('auth:api')->name('auth.logout');
//     Route::get('me', 'me')->middleware('auth:api')->name('auth.session');
// });

// Route::apiResource('session', SessionController::class)->middleware('auth:api');

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', [LoginController::class, 'logout']);

    Route::get('user', [UserController::class, 'current']);

    Route::patch('settings/profile', [ProfileController::class, 'update']);
    Route::patch('settings/password', [PasswordController::class, 'update']);

    Route::post('favorite/{id}', [ListingController::class, 'favoriteProperty']);
    Route::post('unfavorite/{id}', [ListingController::class, 'unFavoriteProperty']);
    Route::get('my_favorites', [ListingController::class, 'myFavorites']);

    Route::get('alerts', [ListingController::class, 'alerts']);
    Route::post('add-alert', [ListingController::class, 'addAlert']);

    Route::get('admin/pages', [PageController::class, 'index']);
    Route::get('admin/pages/{id}', [PageController::class, 'show']);
    Route::post('add-page', [PageController::class, 'store']);
    Route::post('update-page/{id}', [PageController::class, 'update']);
    Route::post('delete-page/{id}', [PageController::class, 'destroy']);

    Route::post('upload-asset', [PageController::class, 'upload']);

    Route::get('users', [UserController::class, 'index']);
    Route::post('change-password/{id}', [UserController::class, 'changePassword']);
    Route::post('delete-user/{id}', [UserController::class, 'deleteUser']);
    Route::post('add-user', [UserController::class, 'addUser']);
    Route::post('change-role', [UserController::class, 'changeRole']);
    Route::post('update-user', [UserController::class, 'updateUser']);

    Route::post('add-agent', [WebAgentController::class, 'store']);
    Route::post('agent-primary/{id}', [WebAgentController::class, 'setAgent']);
    Route::post('update-agent', [WebAgentController::class, 'update']);
    Route::post('delete-agent/{id}', [WebAgentController::class, 'destroy']);

    Route::post('save-settings', [SettingController::class, 'save']);
    Route::post('save-seo', [SettingController::class, 'saveSeo']);

    Route::get('issues', [IssueController::class, 'index']);
    Route::post('add-issue', [IssueController::class, 'store']);
    Route::post('update-issue', [IssueController::class, 'update']);
    Route::post('delete-issue/{id}', [IssueController::class, 'destroy']);
    Route::post('delete-listings-image/{id}', [ListingController::class, 'deleteListingImage']);
});

Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);

Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('password/reset', [ResetPasswordController::class, 'reset']);

Route::post('email/verify/{user}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('email/resend', [VerificationController::class, 'resend']);

Route::post('oauth/{provider}', [OAuthController::class, 'redirect']);
Route::get('oauth/{provider}/callback', [OAuthController::class, 'handleCallback'])->name('oauth.callback');

Route::get('listing/home', [ListingController::class, 'home']);

Route::get('listings', [ListingController::class, 'listings']);
Route::get('listings/{id}', [ListingController::class, 'showListing']);
Route::get('listings-api', [ListingController::class, 'listingsApi']);
Route::get('listings-api/{id}', [ListingController::class, 'showListingsApi']);
Route::post('listings-api', [ListingController::class, 'addListingsApi']);
Route::post('update-listings-api/{id}', [ListingController::class, 'updateListingsApi']);
Route::post('delete-listing/{id}', [ListingController::class, 'deleteListingApi']);

Route::get('listings/state/{state}', [ListingController::class, 'showListingByState']);
Route::post('listings/city/{city}', [ListingController::class, 'showListingByCity']);

Route::get('agents', [WebAgentController::class, 'index']);
Route::get('agents/{slug}', [WebAgentController::class, 'show']);
Route::get('primary-agent', [WebAgentController::class, 'getPrimary']);

Route::post('contact-us', [UserController::class, 'contactUs']);
Route::post('join-agent', [UserController::class, 'joinAgent']);

Route::post('search', [ListingController::class, 'search']);

Route::get('sidebar', [ListingController::class, 'sidebar']);
Route::get('blogs', [PageController::class, 'blogs']);
Route::get('blogs/{slug}', [PageController::class, 'getBlog']);

Route::get('pages/{slug}', [PageController::class, 'getPage']);

Route::get('get-cities', [ListingController::class, 'getCities']);

Route::get('get-settings', [SettingController::class, 'show']);

Route::get('get-seo', [SettingController::class, 'seo']);

Route::get('get-state/{name}', [StateController::class, 'showState']);
Route::get('get-city/{name}', [StateController::class, 'showCity']);
Route::get('get-states', [StateController::class, 'states']);
Route::get('get-city', [StateController::class, 'cities']);
Route::post('save-state/{name}', [StateController::class, 'saveState']);
Route::post('save-city/{name}', [StateController::class, 'saveCity']);

Route::get('search-result/{query}', [StateController::class, 'search']);
Route::post('advanced-search', [StateController::class, 'advancedSearch']);

use App\Models\User;
use App\Models\State;
use App\Models\City;
use App\Models\Property;
use Illuminate\Support\Facades\File;

Route::get('update-user-test', function() {
    $user = User::where('email', 'evilryok@gmail.com')->first();
    $user->role = 'super admin';
    $user->save();
});

Route::get('create-state', function() {
    $states = ['Florida', 'New York'];

    foreach ($states as $key => $state) {
        $exists = State::where('name', $state)->exists();
        if (!$exists) {
            $user = State::create([
                'name' => $state
            ]);
        }
    }
});

Route::get('create-city', function() {
    $cities = Property::where('StateOrProvince', 'FL')
                    ->selectRaw('PropertyType')
                    ->groupBy('PropertyType')
                    ->get();
    $res = [];
    foreach ($cities as $key => $city) {
        $res[] = $city->PropertyType;
    }

    dd($res);

    // $response = Http::get('https://www.google.com/ping?sitemap=https://api.anshell.com/sitemap.xml');
});

Route::get('remove-storage', function() {
    File::deleteDirectory('storage');
});

Route::get('crawl-google', function() {
    $response = Http::get('https://www.google.com/ping?sitemap=https://api.anshell.com/sitemap.xml');
});