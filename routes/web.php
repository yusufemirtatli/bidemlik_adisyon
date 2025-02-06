<?php

use App\Http\Controllers\exports\AdisyonExportController;
use App\Http\Controllers\gider\GiderController;
use App\Http\Controllers\settings\SettingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\table\TableController as Table;
use App\Http\Controllers\Menu\MenuController as Menu;
use App\Http\Controllers\users\UsersController as Users;
use App\Http\Controllers\dashboard\DashboardController as Dashboard;
use App\Http\Controllers\stock\StockController as Stock;
use App\Http\Controllers\shopcart\ShopcartController as Shopcart;

use App\Http\Controllers\layouts\WithoutMenu;
use App\Http\Controllers\layouts\WithoutNavbar;
use App\Http\Controllers\layouts\Fluid;
use App\Http\Controllers\layouts\Container;
use App\Http\Controllers\layouts\Blank;
use App\Http\Controllers\pages\AccountSettingsAccount;
use App\Http\Controllers\pages\AccountSettingsNotifications;
use App\Http\Controllers\pages\AccountSettingsConnections;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\pages\MiscUnderMaintenance;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\cards\CardBasic;
use App\Http\Controllers\user_interface\Accordion;
use App\Http\Controllers\user_interface\Alerts;
use App\Http\Controllers\user_interface\Badges;
use App\Http\Controllers\user_interface\Buttons;
use App\Http\Controllers\user_interface\Carousel;
use App\Http\Controllers\user_interface\Collapse;
use App\Http\Controllers\user_interface\Dropdowns;
use App\Http\Controllers\user_interface\Footer;
use App\Http\Controllers\user_interface\ListGroups;
use App\Http\Controllers\user_interface\Modals;
use App\Http\Controllers\user_interface\Navbar;
use App\Http\Controllers\user_interface\Offcanvas;
use App\Http\Controllers\user_interface\PaginationBreadcrumbs;
use App\Http\Controllers\user_interface\Progress;
use App\Http\Controllers\user_interface\Spinners;
use App\Http\Controllers\user_interface\TabsPills;
use App\Http\Controllers\user_interface\Toasts;
use App\Http\Controllers\user_interface\TooltipsPopovers;
use App\Http\Controllers\user_interface\Typography;
use App\Http\Controllers\extended_ui\PerfectScrollbar;
use App\Http\Controllers\extended_ui\TextDivider;
use App\Http\Controllers\icons\Boxicons;
use App\Http\Controllers\form_elements\BasicInput;
use App\Http\Controllers\form_elements\InputGroups;
use App\Http\Controllers\form_layouts\VerticalForm;
use App\Http\Controllers\form_layouts\HorizontalForm;
use App\Http\Controllers\tables\Basic as TablesBasic;

// layout
Route::get('/layouts/without-menu', [WithoutMenu::class, 'index'])->name('layouts-without-menu');
Route::get('/layouts/without-navbar', [WithoutNavbar::class, 'index'])->name('layouts-without-navbar');
Route::get('/layouts/fluid', [Fluid::class, 'index'])->name('layouts-fluid');
Route::get('/layouts/container', [Container::class, 'index'])->name('layouts-container');
Route::get('/layouts/blank', [Blank::class, 'index'])->name('layouts-blank');

// pages
Route::get('/pages/account-settings-account', [AccountSettingsAccount::class, 'index'])->name('pages-account-settings-account');
Route::get('/pages/account-settings-notifications', [AccountSettingsNotifications::class, 'index'])->name('pages-account-settings-notifications');
Route::get('/pages/account-settings-connections', [AccountSettingsConnections::class, 'index'])->name('pages-account-settings-connections');
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');
Route::get('/pages/misc-under-maintenance', [MiscUnderMaintenance::class, 'index'])->name('pages-misc-under-maintenance');

// authentication
Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');
Route::get('/auth/forgot-password-basic', [ForgotPasswordBasic::class, 'index'])->name('auth-reset-password-basic');

// cards
Route::get('/cards/basic', [CardBasic::class, 'index'])->name('cards-basic');

// User Interface
Route::get('/ui/accordion', [Accordion::class, 'index'])->name('ui-accordion');
Route::get('/ui/alerts', [Alerts::class, 'index'])->name('ui-alerts');
Route::get('/ui/badges', [Badges::class, 'index'])->name('ui-badges');
Route::get('/ui/buttons', [Buttons::class, 'index'])->name('ui-buttons');
Route::get('/ui/carousel', [Carousel::class, 'index'])->name('ui-carousel');
Route::get('/ui/collapse', [Collapse::class, 'index'])->name('ui-collapse');
Route::get('/ui/dropdowns', [Dropdowns::class, 'index'])->name('ui-dropdowns');
Route::get('/ui/footer', [Footer::class, 'index'])->name('ui-footer');
Route::get('/ui/list-groups', [ListGroups::class, 'index'])->name('ui-list-groups');
Route::get('/ui/modals', [Modals::class, 'index'])->name('ui-modals');
Route::get('/ui/navbar', [Navbar::class, 'index'])->name('ui-navbar');
Route::get('/ui/offcanvas', [Offcanvas::class, 'index'])->name('ui-offcanvas');
Route::get('/ui/pagination-breadcrumbs', [PaginationBreadcrumbs::class, 'index'])->name('ui-pagination-breadcrumbs');
Route::get('/ui/progress', [Progress::class, 'index'])->name('ui-progress');
Route::get('/ui/spinners', [Spinners::class, 'index'])->name('ui-spinners');
Route::get('/ui/tabs-pills', [TabsPills::class, 'index'])->name('ui-tabs-pills');
Route::get('/ui/toasts', [Toasts::class, 'index'])->name('ui-toasts');
Route::get('/ui/tooltips-popovers', [TooltipsPopovers::class, 'index'])->name('ui-tooltips-popovers');
Route::get('/ui/typography', [Typography::class, 'index'])->name('ui-typography');

// extended ui
Route::get('/extended/ui-perfect-scrollbar', [PerfectScrollbar::class, 'index'])->name('extended-ui-perfect-scrollbar');
Route::get('/extended/ui-text-divider', [TextDivider::class, 'index'])->name('extended-ui-text-divider');

// icons
Route::get('/icons/boxicons', [Boxicons::class, 'index'])->name('icons-boxicons');

// form elements
Route::get('/forms/basic-inputs', [BasicInput::class, 'index'])->name('forms-basic-inputs');
Route::get('/forms/input-groups', [InputGroups::class, 'index'])->name('forms-input-groups');

// form layouts
Route::get('/form/layouts-vertical', [VerticalForm::class, 'index'])->name('form-layouts-vertical');
Route::get('/form/layouts-horizontal', [HorizontalForm::class, 'index'])->name('form-layouts-horizontal');

// tables
Route::get('/tables/basic', [TablesBasic::class, 'index'])->name('tables-basic');


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
        Route::post('/{id}/delete',[Table::class,'destroy'])->name('masa-delete');
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
  /*                                Gider Routes                                    */
  Route::prefix('gider')->group(function (){
    Route::get('/',[GiderController::class,'index'])->name('gider-index');
    Route::post('/add',[GiderController::class,'create'])->name('gider-add');
    Route::get('/category',[GiderController::class,'category'])->name('gider-category');
    Route::post('/add/category',[GiderController::class,'storeCategory'])->name('add-gider-category');
    Route::post('/category/delete/{id}',[GiderController::class,'destroy'])->name('delete-gider-category');

  });
  ////////////////////////////////////////////////////////////////////////////////////
  /*                                Settings Routes                                    */
  Route::prefix('settings')->group(function (){
    Route::get('/',[SettingController::class,'index'])->name('settings-index');

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
    Route::get('/export-month/adisyons/{month}', [AdisyonExportController::class, 'exportExcelMonth'])->name('export.adisyons.excel.month');

});
