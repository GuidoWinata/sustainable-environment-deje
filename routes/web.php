<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MemberCategoryController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\DetailNewsController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index']);
Route::get('/front/{slug}', [DetailNewsController::class, 'detail']);

Route::get('/admin/auth/login', [AuthController::class, 'login'])->name('login');
Route::post('/admin/auth/login', [AuthController::class, 'doLogin'])->name('do-login');
Route::get('/admin/auth/logout', [AuthController::class, 'doLogout'])->name('do-logout');

// Example Check Access Type
Route::prefix('admin')->middleware(["auth", "access-type:2"])->group(function () {
    Route::get('/xx', [DashboardController::class, 'index']);
});

Route::prefix('admin')->middleware("auth")->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/test', [DashboardController::class, 'test']);
    Route::get('/test2', [DashboardController::class, 'test2']);

    Route::prefix('member')->group(function () {
        Route::get('/', [MemberController::class, 'index']);
        Route::get('/api/search', [MemberController::class, 'searchAPI']);
        Route::get('/add', [MemberController::class, 'add']);
        Route::post('/add', [MemberController::class, 'doCreate']);
        Route::get('/detail/{id}', [MemberController::class, 'detail']);
        Route::get('/update/{id}', [MemberController::class, 'update']);
        Route::post("/update/{id}", [MemberController::class, 'doUpdate']);
        Route::get('/delete/{id}', [MemberController::class, 'doDelete']);
    });

    Route::prefix('product')->group(function () {
        Route::get('/', [ProductsController::class, 'index']);
        Route::get('/add', [ProductsController::class, 'add']);
        Route::post('/add', [ProductsController::class, 'doCreate']);
        Route::get('/detail/{id}', [ProductsController::class, 'detail']);
        Route::get('/update/{id}', [ProductsController::class, 'update']);
        Route::post('/update/{id}', [ProductsController::class, 'doUpdate']);
        Route::get('/delete/{id}', [ProductsController::class, 'doDelete']);
    });

    Route::prefix('product-category')->group(function () {
        Route::get('/', [ProductCategoryController::class, 'index']);
        Route::get('/add', [ProductCategoryController::class, 'add']);
        Route::post('/add', [ProductCategoryController::class, 'doCreate']);
        Route::get('/update/{id}', [ProductCategoryController::class, 'update']);
        Route::post('/update/{id}', [ProductCategoryController::class, 'doUpdate']);
        Route::get('/delete/{id}', [ProductCategoryController::class, 'doDelete']);
    });    
    
    Route::prefix('member-category')->group(function () {
        Route::get('/', [MemberCategoryController::class, 'index']);
        Route::get('/add', [MemberCategoryController::class, 'add']);
        Route::post('/add', [MemberCategoryController::class, 'doCreate']);
        Route::get('/update/{id}', [MemberCategoryController::class, 'update']);
        Route::post("/update/{id}", [MemberCategoryController::class, 'doUpdate']);
        Route::get('/delete/{id}', [MemberCategoryController::class, 'doDelete']);
    });

    Route::prefix('category')->group(function () {
        Route::get('/', [CategoriesController::class, 'index']);
        Route::get('/add', [CategoriesController::class, 'add']);
        Route::post('/add', [CategoriesController::class, 'doCreate']);
        Route::get('/update/{id}', [CategoriesController::class, 'update']);
        Route::post("/update/{id}", [CategoriesController::class, 'doUpdate']);
        Route::get('/delete/{id}', [CategoriesController::class, 'doDelete']);
    });

    Route::prefix('news')->group(function () {
        Route::get('/', [NewsController::class, 'index']);
        Route::get('/add', [NewsController::class, 'add']);
        Route::post('/add', [NewsController::class, 'doCreate']);
        Route::get('/detail/{id}', [NewsController::class, 'detail']);
        Route::get('/update/{id}', [NewsController::class, 'update']);
        Route::post('/update/{id}', [NewsController::class, 'doUpdate']);
        Route::get('/delete/{id}', [NewsController::class, 'doDelete']);
    });

    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/add', [UserController::class, 'add']);
        Route::post('/add', [UserController::class, 'doCreate']);
        // Route::get('/update/{id}', [UserController::class, 'update']);
        Route::post("/update/{id}", [UserController::class, 'doUpdate']);
        Route::get('/update/{id}', [UserController::class, 'update']);
        Route::get('/reset-password/{id}', [UserController::class, 'resetPassword']);
        Route::get('/delete/{id}', [UserController::class, 'doDelete']);
    });
    
    Route::prefix('setting')->group(function () {
        Route::get('/general', [SettingController::class, 'general']);
        Route::post('/general', [SettingController::class, 'doUpdateGeneral']);
        Route::get('/change-password', [SettingController::class, 'changePassword']);
        Route::post('/change-password', [SettingController::class, 'doChangePassword']);
    });
});
