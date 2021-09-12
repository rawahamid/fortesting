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

Route::post('/login', [AuthController::class,'Login']);
Route::post('/guest-signup', [AuthController::class,'Signup']);

Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'dashboard',
], function () {

    //  crud tag
    Route::get('/tags', [TagController::class,'index']);
    Route::post('/tags', [TagController::class,'store']);
    Route::put('/tags/{tag}', [TagController::class,'update']);
    Route::delete('/tags/{tag}', [TagController::class,'destroy']);

    //  crud categories
    Route::get('/categories', [CategoriesController::class,'index']);
    Route::post('/categories', [CategoriesController::class,'store']);
    Route::put('/categories/{category}', [CategoriesController::class,'update']);
    Route::delete('/categories/{category}', [CategoriesController::class,'destroy']);

    //  crud gallery
    Route::get('/gallery', [GalleryController::class,'index']);
    Route::post('/gallery', [GalleryController::class,'store']);
    Route::delete('/gallery/{gallery}', [GalleryController::class,'destroy']);

    //  crud post
    Route::get('/post', [PostController::class,'index']);
    Route::get('/post/{slug}', [PostController::class,'show']);
    Route::post('/post', [PostController::class,'store']);
    Route::put('/post/{post}', [PostController::class,'update']);
    Route::delete('/post/{post}', [PostController::class,'destroy']);

    //index roles
    Route::get('/roles', [RolesController::class,'index']);

    // all user
    Route::get('/users', [AuthController::class,'index']);
    // create admin
    Route::post('/create-admin', [AuthController::class,'CreateAdmin']);
});
