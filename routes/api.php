<?php

use App\Http\Controllers\API\AssociationAPIController;
use App\Http\Controllers\API\BatchDataController;
use App\Http\Controllers\API\CounsellingAPIController;
use App\Http\Controllers\API\DisabilitiesController;
use App\Http\Controllers\API\District_UnionAPIController;
use App\Http\Controllers\API\DistrictAPIController;
use App\Http\Controllers\API\EventApiController;
use App\Http\Controllers\API\InnovationApiController;
use App\Http\Controllers\API\JobApiController;
use App\Http\Controllers\API\NewsPostApiController;
use App\Http\Controllers\API\OPD;
use App\Http\Controllers\API\PersonController;
use App\Http\Controllers\API\PWDProfileController;
use App\Http\Controllers\API\ProductServiceAPIController;
use App\Http\Controllers\API\ServiceProviderAPIController;
use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ApiResurceController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::POST("users/register", [ApiAuthController::class, "register"]);
Route::POST("users/login", [ApiAuthController::class, "login"]);
Route::POST('logout', [ApiAuthController::class, "logout"]);
Route::resource('people', PersonController::class);
Route::POST('people-v2', [PersonController::class, 'storeOrUpdate']);
Route::resource('district-unions', District_UnionAPIController::class);
Route::resource('opds', OPD::class);
Route::resource('service-providers', ServiceProviderAPIController::class);
Route::resource('jobs', JobApiController::class);
Route::resource('innovations', InnovationApiController::class);
Route::resource('events', EventApiController::class);
Route::resource('news-posts', NewsPostApiController::class);
Route::resource('counselling-centres', CounsellingAPIController::class);
Route::resource('associations', AssociationAPIController::class);
Route::resource('products', ProductServiceAPIController::class);
Route::apiResource('disabilities', DisabilitiesController::class);
Route::apiResource('districts', DistrictAPIController::class);
Route::apiResource('people-batch-collection', BatchDataController::class);

// PWD Profile API Routes
Route::prefix('pwd-profiles')->group(function () {
    Route::get('/', [PWDProfileController::class, 'index']);
    Route::get('/statistics', [PWDProfileController::class, 'statistics']);
    Route::get('/disabilities', [PWDProfileController::class, 'getDisabilities']);
    Route::post('/bulk-sync', [PWDProfileController::class, 'bulkSync']);
    Route::get('/{id}', [PWDProfileController::class, 'show']);
    Route::post('/', [PWDProfileController::class, 'store']);
    Route::put('/{id}', [PWDProfileController::class, 'update']);
    Route::delete('/{id}', [PWDProfileController::class, 'destroy']);
});


// Ogiki Moses Odera

// Route::post('send-email', [ResetPasswordController::class, 'sendEmail']);
//Route::post('reset-password', [ResetPasswordController::class, 'reset']);

// Route::get('form-password', [ResetPasswordController::class, 'showResetForm']);
// Route::post('reset-password', [ResetPasswordController::class, 'reset']);

Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);

// Route::POST("people", [ApiResurceController::class, "person_create"]);
// Route::PUT("people/{id}", [ApiResurceController::class, "person_update"]);
// Route::get("people", [ApiResurceController::class, "people"]);
// Route::get("jobs", [ApiResurceController::class, "jobs"]);
Route::get('api/{model}', [ApiResurceController::class, 'index']);
// Route::get('groups', [ApiResurceController::class, 'groups']);
// Route::get('associations', [ApiResurceController::class, 'associations']);
// Route::get('institutions', [ApiResurceController::class, 'institutions']);
// Route::get('service-providers', [ApiResurceController::class, 'service_providers']);
// Route::get('counselling-centres', [ApiResurceController::class, 'counselling_centres']);
// Route::get('products', [ApiResurceController::class, 'products']);
// Route::get('events', [ApiResurceController::class, 'events']);
// Route::get('news-posts', [ApiResurceController::class, 'news_posts']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('ajax', function (Request $r) {

    $_model = trim($r->get('model'));
    $conditions = [];
    foreach ($_GET as $key => $v) {
        if (substr($key, 0, 6) != 'query_') {
            continue;
        }
        $_key = str_replace('query_', "", $key);
        $conditions[$_key] = $v;
    }

    if (strlen($_model) < 2) {
        return [
            'data' => []
        ];
    }

    $model = "App\Models\\" . $_model;
    $search_by_1 = trim($r->get('search_by_1'));
    $search_by_2 = trim($r->get('search_by_2'));

    $q = trim($r->get('q'));

    $res_1 = $model::where(
        $search_by_1,
        'like',
        "%$q%"
    )
        ->where($conditions)
        ->limit(20)->get();
    $res_2 = [];

    if ((count($res_1) < 20) && (strlen($search_by_2) > 1)) {
        $res_2 = $model::where(
            $search_by_2,
            'like',
            "%$q%"
        )
            ->where($conditions)
            ->limit(20)->get();
    }

    $data = [];
    foreach ($res_1 as $key => $v) {
        $name = "";
        if (isset($v->name)) {
            $name = " - " . $v->name;
        }
        $data[] = [
            'id' => $v->id,
            'text' => "#$v->id" . $name
        ];
    }
    foreach ($res_2 as $key => $v) {
        $name = "";
        if (isset($v->name)) {
            $name = " - " . $v->name;
        }
        $data[] = [
            'id' => $v->id,
            'text' => "#$v->id" . $name
        ];
    }

    return [
        'data' => $data
    ];
});
