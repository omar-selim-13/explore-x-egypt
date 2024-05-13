<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\ArtifactController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthUserController;
use App\Http\Controllers\Api\AuthAdminController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Middleware\Admin;


Route::get('artifacts/get_artifact_by_location_id', [ArtifactController::class, 'get_artifact_by_location_id'])->middleware('auth:sanctum');

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//Route::apiResource('/locations',LocationController::class);

/* Route::get('/locations/search',[LocationController::class,'search']);
Route::get('/locations', [LocationController::class, 'index']);
Route::post('/locations', [LocationController::class, 'store']);
Route::get('/locations/{location}', [LocationController::class, 'show']);
Route::post('/locations/{location}', [LocationController::class, 'update']);
Route::delete('/locations/{location}', [LocationController::class, 'destroy']); */



 

// Route::prefix('admin')->controller(AuthAdminController::class)->group(function () {
//     Route::post('login', 'login');
//   //  Route::post('logout', 'logout')->middleware('admins');
   
// });

// // Route::post('/admin/login', 'AuthAdminController@login');
// // Route::post('/admin/logout', 'AuthAdminController@logout')->middleware('admin');


// Route::group(['prefix' => 'admin', 'middleware' => 'App\Http\Middleware\Admin'], function () {
//     Route::post('logout', 'AuthAdminController@logout');


// });

              //  ------------- ADMIN  API ----------- //

                  // -- Admin Auth Api -- //

Route::prefix('admin')->controller(AuthAdminController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout')->middleware(['auth:sanctum']);
   
});


Route::prefix('admin')->middleware(['auth:sanctum'])->group(function () { 
                    // -- Location Api -- //

 Route::prefix('locations')->middleware(['auth:sanctum'])->controller(LocationController::class, '')->group(function () {
    Route::get('/search', 'search')->name('locations.search');
    Route::get('/', 'index')->name('locations.index');
    Route::post('/', 'store')->name('locations.store');
    Route::get('/{location}', 'show')->name('locations.show');
    Route::put('/{location}', 'update')->name('locations.update');
    Route::delete('/{location}', 'destroy')->name('locations.destroy');
});


                        // -- Artifact Api -- //

Route::prefix('artifacts')->controller(ArtifactController::class, '')->group(function () {
    Route::get('/search', 'search')->name('artifacts.search');
    Route::get('/', 'index')->name('artifacts.index');
    Route::post('/', 'store')->name('artifacts.store');
    Route::get('/{artifact}', 'show')->name('artifacts.show');
    Route::put('/{artifacts}', 'update')->name('artifacts.update');
    Route::delete('/{artifact}', 'destroy')->name('artifacts.destroy');
});


                        // -- City Api -- //

Route::prefix('cities')->controller(CityController::class, '')->group(function () {
    Route::get('/search', 'search')->name('cities.search');
    Route::get('/', 'index')->name('cities.index');
    Route::post('/', 'store')->name('cities.store');
    Route::get('/{id}', 'show')->name('cities.show');
    Route::put('/{id}', 'update')->name('cities.update');
    Route::delete('/{id}', 'destroy')->name('cities.destroy');
});


                       // -- Admin Api -- //

Route::prefix('admins')->controller(AdminController::class, '')->group(function () {
    Route::get('/search', 'search')->name('admins.search');
    Route::get('/', 'index')->name('admins.index');
    Route::get('/{id}', 'show')->name('admins.show');
//  Route::post('/', 'store')->name('admins.store');
//  Route::put('/{id}', 'update')->name('admins.update');
    Route::delete('/{id}', 'destroy')->name('admins.destroy');
});

                      // -- User Api -- //

Route::prefix('users')->controller(UserController::class, '')->group(function () {
    Route::get('/search', 'search')->name('users.search');
    Route::get('/', 'index')->name('users.index');
    Route::get('/{id}', 'show')->name('users.show');
 //   Route::post('/', 'store')->name('users.store');
 //   Route::put('/{id}', 'update')->name('users.update');
    Route::delete('/{id}', 'destroy')->name('users.destroy');
});


                      // -- Favorite Api -- //

 Route::prefix('favorites')->middleware('auth:sanctum')->controller(FavoriteController::class, '')->group(function () {
    Route::get('/search', 'search')->name('favorites.search');
    Route::get('/showUserFavorite', 'showUserFavorite')->name('favorites.showUserFavorite');
    Route::post('/', 'store')->name('favorites.store');
    Route::get('/', 'index')->name('favorites.index');
    Route::get('/{id}', 'show')->name('favorites.show');
    Route::delete('/{id}', 'destroy')->name('favorites.destroy');
});
});

 


              //  ------------- USER  API ----------- //

                     // -- User Auth Api -- //

Route::controller(AuthUserController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout')->middleware('auth:sanctum');
   
});

                     // -- User Location Api -- //

 Route::prefix('locations')->middleware('auth:sanctum')->controller(LocationController::class, '')->group(function () {
    Route::get('/search', 'search')->name('locations.search');
    Route::get('/', 'index')->name('locations.index');
    Route::get('/{location}', 'show')->name('locations.show');
});


                       // -- User Artifact Api -- //

Route::prefix('artifacts')->middleware('auth:sanctum')->controller(ArtifactController::class, '')->group(function () {
    Route::get('/search', 'search')->name('artifacts.search');
    Route::get('/', 'index')->name('artifacts.index');
    Route::get('/{artifact}', 'show')->name('artifacts.show');
   // Route::get('/get_artifact_by_location_id' , 'get_artifact_by_location_id');

});


                      // -- User Favorite Api -- //

Route::prefix('favorites')->middleware('auth:sanctum')->controller(FavoriteController::class, '')->group(function () {
    Route::get('/showUserFavorite', 'showUserFavorite')->name('favorites.showUserFavorite');
    Route::get('/', 'index')->name('favorites.index');
    Route::get('/{id}', 'show')->name('favorites.show');
    Route::post('/', 'store')->name('favorites.store');

});
