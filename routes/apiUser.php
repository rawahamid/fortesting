<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\TagController;
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

//   api
Route::group([
   
], function () {

    //  all tag
    Route::get('/tags', [TagController::class,'index']);
  

    //  all categories
    Route::get('/categories', [CategoriesController::class,'index']);

    //  crud post
    Route::get('/post', [PostController::class,'index']);
    Route::get('/post/{slug}', [PostController::class,'show']);
 
    // all user
    Route::get('/users', [AuthController::class,'index']);
});
