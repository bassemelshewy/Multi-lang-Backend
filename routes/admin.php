<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\LanguagesController;
use App\Http\Controllers\Admin\MainCategoryController;
use App\Http\Controllers\Admin\SubCategoriesController;
use App\Http\Controllers\Admin\VendorsController;
use Illuminate\Support\Facades\Route;


// Route::get('/', function () {
//     return 'hello';
// });

define('PAGINATION_COUNT', 10);

Route::group(['middleware' => 'guest:admin'], function () {
    Route::get('login', [LoginController::class, 'getLogin'])->name('get.admin.login');
    Route::post('login', [LoginController::class, 'login'])->name('admin.login');
});

Route::group(['middleware' => 'auth:admin'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('logout', [LoginController::class, 'destroy'])->name('admin.logout');

    ######################### Begin Languages Route ########################
    Route::group(['prefix' => 'languages'], function () {
        Route::get('/', [LanguagesController::class, 'index'])->name('admin.languages');

        Route::get('create', [LanguagesController::class, 'create'])->name('admin.languages.create');
        Route::post('store', [LanguagesController::class, 'store'])->name('admin.languages.store');

        Route::get('edit/{id}', [LanguagesController::class, 'edit'])->name('admin.languages.edit');
        Route::put('update/{id}', [LanguagesController::class, 'update'])->name('admin.languages.update');

        Route::get('delete/{id}', [LanguagesController::class, 'destroy'])->name('admin.languages.delete');
        // Route::delete('delete/{id}','LanguagesController@destroy') -> name('admin.languages.delete');

        Route::get('changeStatus/{id}',  [LanguagesController::class, 'changeStatus'])->name('admin.languages.status');
    });
    ######################### End Languages Route ########################


    ######################### Begin Main Categoris Routes ########################
    Route::group(['prefix' => 'main_categories'], function () {
        Route::get('/', [MainCategoryController::class, 'index'])->name('admin.maincategories');

        Route::get('create', [MainCategoryController::class, 'create'])->name('admin.maincategories.create');
        Route::post('store', [MainCategoryController::class, 'store'])->name('admin.maincategories.store');

        Route::get('edit/{id}', [MainCategoryController::class, 'edit'])->name('admin.maincategories.edit');
        Route::put('update/{id}', [MainCategoryController::class, 'update'])->name('admin.maincategories.update');

        Route::get('delete/{id}', [MainCategoryController::class, 'destroy'])->name('admin.maincategories.delete');
        Route::get('changeStatus/{id}',  [MainCategoryController::class, 'changeStatus'])->name('admin.maincategories.status');
    });
    ######################### End  Main Categoris Routes  ########################


    // ######################### Begin Sub Categoris Routes ########################
    // Route::group(['prefix' => 'sub_categories'], function () {
    //     Route::get('/', [SubCategoriesController::class , 'index'])->name('admin.subcategories');
    //     Route::get('create', [SubCategoriesController::class , 'create'])->name('admin.subcategories.create');
    //     Route::post('store', [SubCategoriesController::class , 'store'])->name('admin.subcategories.store');
    //     Route::get('edit/{id}', [SubCategoriesController::class , 'edit'])->name('admin.subcategories.edit');
    //     Route::post('update/{id}', [SubCategoriesController::class , 'update'])->name('admin.subcategories.update');
    //     Route::get('delete/{id}', [SubCategoriesController::class , 'destroy'])->name('admin.subcategories.delete');
    //     Route::get('changeStatus/{id}', [SubCategoriesController::class , 'changestatus'])->name('admin.subcategories.status');
    // });
    // ######################### End  Sub Categoris Routes  ########################


    ######################### Begin vendors Routes ########################
    Route::group(['prefix' => 'vendors'], function () {
        Route::get('/', [VendorsController::class, 'index']) -> name('admin.vendors');

        Route::get('create',[VendorsController::class, 'create']) -> name('admin.vendors.create');
        Route::post('store', [VendorsController::class, 'store']) -> name('admin.vendors.store');

        Route::get('edit/{id}',[VendorsController::class, 'edit']) -> name('admin.vendors.edit');
        Route::post('update/{id}',[VendorsController::class, 'update']) -> name('admin.vendors.update');

        Route::get('delete/{id}',[VendorsController::class, 'destroy']) -> name('admin.vendors.delete');

        Route::get('changeStatus/{id}', [VendorsController::class , 'changestatus'])->name('admin.vendors.status');
    });

    ######################### End  vendors Routes  ########################
});
