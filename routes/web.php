<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\YearController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AtrrController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExtraController;
use App\Http\Controllers\InsuranceCompanyController;
use App\Http\Controllers\LocationController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('home', [HomeController::class, 'index'])->name('home');
Route::get('/switch-language/{locale}', [HomeController::class, 'switchLanguage'])->name('switch_language');
// category dress
Route::get('category', [CategoryController::class, 'index'])->name('category');
Route::post('add_category', [CategoryController::class, 'add_category'])->name('add_category');
Route::get('show_category', [CategoryController::class, 'show_category'])->name('show_category');
Route::post('edit_category', [CategoryController::class, 'edit_category'])->name('edit_category');
Route::post('update_category', [CategoryController::class, 'update_category'])->name('update_category');
Route::post('delete_category', [CategoryController::class, 'delete_category'])->name('delete_category');
// brand dress
Route::get('brand', [BrandController::class, 'index'])->name('brand');
Route::post('add_brand', [BrandController::class, 'add_brand'])->name('add_brand');
Route::get('show_brand', [BrandController::class, 'show_brand'])->name('show_brand');
Route::post('edit_brand', [BrandController::class, 'edit_brand'])->name('edit_brand');
Route::post('update_brand', [BrandController::class, 'update_brand'])->name('update_brand');
Route::post('delete_brand', [BrandController::class, 'delete_brand'])->name('delete_brand');

// size dress
Route::get('insurancecompany', [InsuranceCompanyController::class, 'index'])->name('insurancecompany');
Route::post('add_insurance_company', [InsuranceCompanyController::class, 'add_insurance_company'])->name('add_insurance_company');
Route::get('show_insurance_company', [InsuranceCompanyController::class, 'show_insurance_company'])->name('show_insurance_company');
Route::post('edit_insurance_company', [InsuranceCompanyController::class, 'edit_insurance_company'])->name('edit_insurance_company');
Route::post('update_insurance_company', [InsuranceCompanyController::class, 'update_insurance_company'])->name('update_insurance_company');
Route::post('delete_insurance_company', [InsuranceCompanyController::class, 'delete_insurance_company'])->name('delete_insurance_company');

// color dress
Route::get('color', [ColorController::class, 'index'])->name('color');
Route::post('add_color', [ColorController::class, 'add_color'])->name('add_color');
Route::get('show_color', [ColorController::class, 'show_color'])->name('show_color');
Route::post('edit_color', [ColorController::class, 'edit_color'])->name('edit_color');
Route::post('update_color', [ColorController::class, 'update_color'])->name('update_color');
Route::post('delete_color', [ColorController::class, 'delete_color'])->name('delete_color');

// car car
Route::get('car', [CarController::class, 'index'])->name('car');
Route::post('add_car', [CarController::class, 'add_car'])->name('add_car');
Route::get('show_car', [CarController::class, 'show_car'])->name('show_car');
Route::post('edit_car', [CarController::class, 'edit_car'])->name('edit_car');
Route::post('update_car', [CarController::class, 'update_car'])->name('update_car');
Route::post('delete_car', [CarController::class, 'delete_car'])->name('delete_car');
Route::post('remove_attachments', [CarController::class, 'remove_attachments'])->name('remove_attachments');
Route::post('e_remove_attachments', [CarController::class, 'e_remove_attachments'])->name('e_remove_attachments');
Route::post('upload_attachments', [CarController::class, 'upload_attachments'])->name('upload_attachments');
Route::post('maint_car', [CarController::class, 'maint_car'])->name('maint_car');
Route::get('maint_car_all', [CarController::class, 'maint_car_all'])->name('maint_car_all');
Route::get('show_maint_car', [CarController::class, 'show_maint_car'])->name('show_maint_car');
Route::post('maint_car_comp', [CarController::class, 'maint_car_comp'])->name('maint_car_comp');
Route::get('car_profile/{id}', [CarController::class, 'car_profile'])->name('car_profile');
Route::post('car_profile_data', [CarController::class, 'car_profile_data'])->name('car_profile_data');
Route::post('get_car_models', [CarController::class, 'get_car_models'])->name('get_car_models');
Route::post('get_car_years', [CarController::class, 'get_car_years'])->name('get_car_years');
Route::post('get_car_price', [CarController::class, 'get_car_price'])->name('get_car_price');


// booking
Route::get('booking', [BookingController::class, 'index'])->name('booking');
Route::post('get_dress_detail', [BookingController::class, 'get_dress_detail'])->name('get_dress_detail');
Route::post('add_booking', [BookingController::class, 'add_booking'])->name('add_booking');
Route::get('view_booking', [BookingController::class, 'view_booking'])->name('view_booking');
Route::get('show_booking', [BookingController::class, 'show_booking'])->name('show_booking');
Route::post('search_customer', [BookingController::class, 'search_customer']);
Route::post('add_booking_customer', [BookingController::class, 'add_booking_customer'])->name('add_booking_customer');
Route::post('get_payment', [BookingController::class, 'get_payment'])->name('get_payment');
Route::post('add_payment', [BookingController::class, 'add_payment'])->name('add_payment');
Route::post('add_dress_availability', [BookingController::class, 'add_dress_availability'])->name('add_dress_availability');
Route::post('get_booking_detail', [BookingController::class, 'get_booking_detail'])->name('get_booking_detail');
Route::post('delete_payment', [BookingController::class, 'delete_payment'])->name('delete_payment');
Route::post('delete_booking', [BookingController::class, 'delete_booking'])->name('delete_booking');
Route::get('edit_booking/{id}', [BookingController::class, 'edit_booking'])->name('edit_booking');
Route::post('update_booking', [BookingController::class, 'update_booking'])->name('update_booking');
Route::post('cancel_booking', [BookingController::class, 'cancel_booking'])->name('cancel_booking');
Route::post('get_booking_data', [BookingController::class, 'get_booking_data'])->name('get_booking_data');
Route::post('get_extend_dress_detail', [BookingController::class, 'get_extend_dress_detail'])->name('get_extend_dress_detail');
Route::post('add_extend_booking', [BookingController::class, 'add_extend_booking'])->name('add_extend_booking');
Route::post('get_finish_booking_detail', [BookingController::class, 'get_finish_booking_detail'])->name('get_finish_booking_detail');
Route::post('add_finish_booking', [BookingController::class, 'add_finish_booking'])->name('add_finish_booking');
Route::get('a4_bill/{id}', [BookingController::class, 'a4_bill'])->name('a4_bill');
Route::get('receipt_bill/{booking_no}', [BookingController::class, 'receipt_bill'])->name('receipt_bill');
Route::post('search_available_car', [BookingController::class, 'search_available_car'])->name('search_available_car');



// customer dress
Route::get('customer', [CustomerController::class, 'index'])->name('customer');
Route::post('add_customer', [CustomerController::class, 'add_customer'])->name('add_customer');
Route::get('show_customer', [CustomerController::class, 'show_customer'])->name('show_customer');
Route::post('edit_customer', [CustomerController::class, 'edit_customer'])->name('edit_customer');
Route::post('update_customer', [CustomerController::class, 'update_customer'])->name('update_customer');
Route::post('delete_customer', [CustomerController::class, 'delete_customer'])->name('delete_customer');
Route::get('customer_profile/{id}', [CustomerController::class, 'customer_profile'])->name('customer_profile');
Route::post('customer_profile_data', [CustomerController::class, 'customer_profile_data'])->name('customer_profile_data');
//user
Route::match(['get', 'post'], 'login_page', [UserController::class, 'login_page'])->name('login_page');
Route::match(['get', 'post'], 'login', [UserController::class, 'login'])->name('login');
Route::match(['get', 'post'], 'logout', [UserController::class, 'logout'])->name('logout');
Route::get('user', [UserController::class, 'index'])->name('user');
Route::post('add_user', [UserController::class, 'add_user'])->name('add_user');
Route::get('show_user', [UserController::class, 'show_user'])->name('show_user');
Route::post('edit_user', [UserController::class, 'edit_user'])->name('edit_user');
Route::post('update_user', [UserController::class, 'update_user'])->name('update_user');
Route::post('delete_user', [UserController::class, 'delete_user'])->name('delete_user');


Route::get('expense_category', [ExpenseCategoryController::class, 'index'])->name('expense_category');
Route::post('add_expense_category', [ExpenseCategoryController::class, 'add_expense_category'])->name('add_expense_category');
Route::get('show_expense_category', [ExpenseCategoryController::class, 'show_expense_category'])->name('show_expense_category');
Route::post('edit_expense_category', [ExpenseCategoryController::class, 'edit_expense_category'])->name('edit_expense_category');
Route::post('update_expense_category', [ExpenseCategoryController::class, 'update_expense_category'])->name('update_expense_category');
Route::post('delete_expense_category', [ExpenseCategoryController::class, 'delete_expense_category'])->name('delete_expense_category');

// expense_categoryController Routes

Route::get('expense', [ExpenseController::class, 'index'])->name('expense');
Route::post('add_expense', [ExpenseController::class, 'add_expense'])->name('add_expense');
Route::get('show_expense', [ExpenseController::class, 'show_expense'])->name('show_expense');
Route::post('edit_expense', [ExpenseController::class, 'edit_expense'])->name('edit_expense');
Route::post('update_expense', [ExpenseController::class, 'update_expense'])->name('update_expense');
Route::post('delete_expense', [ExpenseController::class, 'delete_expense'])->name('delete_expense_category');
Route::get('download_expense_image/{id}', [ExpenseController::class, 'download_expense_image'])->name('download_expense_image');


// AccountController Routes

Route::get('account', [AccountController::class, 'index'])->name('account');
Route::post('add_account', [AccountController::class, 'add_account'])->name('add_account');
Route::get('show_account', [AccountController::class, 'show_account'])->name('show_account');
Route::post('edit_account', [AccountController::class, 'edit_account'])->name('edit_account');
Route::post('update_account', [AccountController::class, 'update_account'])->name('update_account');
Route::post('delete_account', [AccountController::class, 'delete_account'])->name('delete_account');


//sms
Route::get('sms', [SmsController::class, 'index'])->name('sms');
Route::post('get_sms_status', [SmsController::class, 'get_sms_status'])->name('get_sms_status');
Route::match(['get', 'post'], 'add_status_sms', [SmsController::class, 'add_status_sms'])->name('add_status_sms');


//Settings
Route::get('setting', [SettingController::class, 'setting'])->name('setting');
Route::post('add_setting', [SettingController::class, 'add_setting'])->name('add_setting');
Route::get('setting_data', [SettingController::class, 'setting_data'])->name('setting_data');
Route::post('dress_avail', [SettingController::class, 'dress_avail'])->name('dress_avail');

// model
Route::get('model', [ModelController::class, 'index'])->name('model');
Route::post('add_model', [ModelController::class, 'add_model'])->name('add_model');
Route::get('show_model', [ModelController::class, 'show_model'])->name('show_model');
Route::post('edit_model', [ModelController::class, 'edit_model'])->name('edit_model');
Route::post('update_model', [ModelController::class, 'update_model'])->name('update_model');
Route::post('delete_model', [ModelController::class, 'delete_model'])->name('delete_model');

// year
Route::get('year', [YearController::class, 'index'])->name('year');
Route::post('add_year', [YearController::class, 'add_year'])->name('add_year');
Route::get('show_year', [YearController::class, 'show_year'])->name('show_year');
Route::post('edit_year', [YearController::class, 'edit_year'])->name('edit_year');
Route::post('update_year', [YearController::class, 'update_year'])->name('update_year');
Route::post('delete_year', [YearController::class, 'delete_year'])->name('delete_year');
Route::post('get_models', [YearController::class, 'get_models'])->name('get_models');



//service

Route::get('service', [ServiceController::class, 'index'])->name('service');
Route::post('add_service', [ServiceController::class, 'add_service'])->name('add_service');
Route::get('show_service', [ServiceController::class, 'show_service'])->name('show_service');
Route::post('edit_service', [ServiceController::class, 'edit_service'])->name('edit_service');
Route::post('update_service', [ServiceController::class, 'update_service'])->name('update_service');
Route::post('delete_service', [ServiceController::class, 'delete_service'])->name('delete_service');
Route::post('search_car', [ServiceController::class, 'search_car'])->name('search_car');

//Attributes
Route::get('attr', [AtrrController::class, 'index'])->name('attr');
Route::post('add_attr', [AtrrController::class, 'add_attr'])->name('add_attr');
Route::get('show_attr', [AtrrController::class, 'show_attr'])->name('show_attr');
Route::post('edit_attr', [AtrrController::class, 'edit_attr'])->name('edit_attr');
Route::post('update_attr', [AtrrController::class, 'update_attr'])->name('update_attr');
Route::post('delete_attr', [AtrrController::class, 'delete_attr'])->name('delete_attr');


//Extra
Route::get('extra', [ExtraController::class, 'index'])->name('extra');
Route::post('add_extra', [ExtraController::class, 'add_extra'])->name('add_extra');
Route::get('show_extra', [ExtraController::class, 'show_extra'])->name('show_extra');
Route::post('edit_extra', [ExtraController::class, 'edit_extra'])->name('edit_extra');
Route::post('update_extra', [ExtraController::class, 'update_extra'])->name('update_extra');
Route::post('delete_extra', [ExtraController::class, 'delete_extra'])->name('delete_extra');

//Location
Route::get('location', [LocationController::class, 'index'])->name('location');
Route::post('add_location', [LocationController::class, 'add_location'])->name('add_location');
Route::get('show_location', [LocationController::class, 'show_location'])->name('show_location');
Route::post('edit_location', [LocationController::class, 'edit_location'])->name('edit_location');
Route::post('update_location', [LocationController::class, 'update_location'])->name('update_location');
Route::post('delete_location', [LocationController::class, 'delete_location'])->name('delete_location');
