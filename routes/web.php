<?php

use App\Http\Controllers\exports\AdisyonExportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\table\TableController as Table;
use App\Http\Controllers\Menu\MenuController as Menu;
use App\Http\Controllers\users\UsersController as Users;
use App\Http\Controllers\dashboard\DashboardController as Dashboard;
use App\Http\Controllers\stock\StockController as Stock;
use App\Http\Controllers\shopcart\ShopcartController as Shopcart;

Route::get('/', function () {
    return redirect()->route('login-page');
});

/*                                MY ROUTES                                       */
////////////////////////////////////////////////////////////////////////////////////
///*                                Auth Routes                                    */
Route::get('/login',[\App\Http\Controllers\users\UsersController::class,'loginPage'])->name('login-page');
Route::post('/login',[\App\Http\Controllers\users\UsersController::class,'login'])->name('login');
////////////////////////////////////////////////////////////////////////////////////
/*                                Table Routes                                    */
Route::middleware('auth')->group(function () {
    Route::prefix('masa')->group(function (){
        Route::get('/',[Table::class,'index'])->name('masa');
        Route::get('/{id}',[Table::class,'detail'])->name('masa_detail');
        Route::post('/tablecreate',[Table::class,'store'])->name('masa_create');
    });
////////////////////////////////////////////////////////////////////////////////////
    /*                                Menu Routes                                    */
    Route::prefix('menu')->group(function (){
        Route::get('/',[Menu::class,'index'])->name('menu-product');
        Route::post('/addproduct',[Menu::class,'store'])->name('add_product');
        Route::get('/category',[Menu::class,'category'])->name('menu-category');
        Route::post('/category/add',[Menu::class,'addCategory'])->name('menu-add-category');
        Route::post('/category/delete/{id}',[Menu::class,'deleteCategory'])->name('menu-delete-category');
        Route::get('/detail/{id}',[Menu::class,'foodDetail'])->name('menu-food-detail');
        Route::post('/detail/{id}/update',[Menu::class,'foodDetailUpdate'])->name('menu-food-detail-update');

    });
////////////////////////////////////////////////////////////////////////////////////
    /*                                Stock Routes                                    */
    Route::prefix('stock')->group(function (){
        Route::get('/',[Stock::class,'index'])->name('stock-index');
        Route::get('/add',[Stock::class,'create'])->name('stock-add');
    });
////////////////////////////////////////////////////////////////////////////////////
    /*                                Shopcart Routes                                    */
    Route::prefix('shopcart')->group(function (){
    });

    Route::get('/users',[Users::class,'index'])->name('kullanıcılar');
    Route::get('/dashboard',[Dashboard::class,'index'])->name('dashboard');

////////////////////////////////////////////////////////////////////////////////////
    /*                                Ajax Routes                                    */
    Route::post('/update-product-shopcart', [Shopcart::class, 'update'])->name('update-product-shopcart');
    Route::post('/update-database',[Shopcart::class,'updateDatabase']);
    Route::post('/update-database-paid',[Shopcart::class,'updateDatabasePaid']);
    Route::post('/update-database-refund',[Shopcart::class,'updateDatabaseRefund']);
    Route::post('/update-table-totals',[Table::class,'updateTableTotals']);
////////////////////////////////////////////////////////////////////////////////////
    /*                                Export Routes                                    */
    Route::get('/export/adisyons/{day}', [AdisyonExportController::class, 'exportExcel'])->name('export.adisyons.excel');

});
