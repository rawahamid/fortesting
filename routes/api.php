<?php

use App\Http\Controllers\{
    AuthController, CategoriesController, GalleryController,
    PostController, RolesController, TagController
};
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class,'Login']);
Route::post('/guest-signup', [AuthController::class,'Signup']);

Route::group(['middleware' => 'auth:api', 'prefix' => 'dashboard'], function () {

    //  crud tag
    Route::group(['prefix' => 'tags'], function () {
        Route::get('/',         [TagController::class, 'index']);
        Route::post('/',        [TagController::class, 'store']);
        Route::put('/{tag}',    [TagController::class, 'update']);
        Route::delete('/{tag}', [TagController::class, 'destroy']);
    });

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
