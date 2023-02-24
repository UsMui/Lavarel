<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/',[App\Http\Controllers\WebController::class,"home"] );
Route::get('about-us',[App\Http\Controllers\WebController::class,"aboutUs"]);

// category
Route::middleware(["auth","admin"])->prefix("admin")->group(function (){
    include_once("admin.php");

});
Route::get("/admin/product",[\App\Http\Controllers\Admin\ProductController::class,"listAll"])->middleware("auth");

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Route::get("user/product/detail/{product}",[\App\Http\Controllers\Admin\ProductController::class,"detail"]);
Route::get("user/product/shopgrid",[\App\Http\Controllers\Admin\ProductController::class,"shopgrid"]);
Route::get("user/product/shopcart",[\App\Http\Controllers\WebController::class,"shopcart"]);
Route::get("detail/{product}", [App\Http\Controllers\WebController::class, 'detail'])->name("product_detail");
Route::post("/add_to_cart/{product}",[App\Http\Controllers\WebController::class, 'addToCart'])->name("add_to_cart");
Route::get("user/product/checkout",[\App\Http\Controllers\WebController::class,"checkout"]);
Route::get("/remove-cart/{product}",[\App\Http\Controllers\WebController::class,"remove"]);
Route::post("user/product/checkout",[\App\Http\Controllers\WebController::class,"placeOrder"]);
Route::get("/sendNotification",[\App\Http\Controllers\WebController::class,"sendNotification"]);

Route::get("/admin/student",[App\Http\Controllers\Admin\StudentController::class, "listAll"]);
Route::get("/admin/student/create",[App\Http\Controllers\Admin\StudentController::class, "createStudent"]);
Route::post("/admin/student/create",[App\Http\Controllers\Admin\StudentController::class, "store"]);



