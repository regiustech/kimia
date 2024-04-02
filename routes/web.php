<?php
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\VariantController;
use App\Http\Controllers\Admin\VariantDetailController;
use App\Http\Controllers\Admin\OrderController;

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

Route::get("/",[FrontendController::class,"index"])->name("home");
Route::get("/about-us",[FrontendController::class,"about"])->name("about");
Route::get("/services",[FrontendController::class,"services"])->name("services");
Route::get("/contact-us",[FrontendController::class,"contacts"])->name("contacts");
Route::post("/contact/send",[FrontendController::class,"sendContacts"])->name("contacts.send");
Route::get("/products",[FrontendController::class,"products"])->name("products");
Route::post("/products/filter",[FrontendController::class,"filterProducts"])->name("products.filter");

Route::get("/products/{slug}",[FrontendController::class,"productByCategory"])->name("productByCat");
Route::get("/product/{slug}",[FrontendController::class,"productDetail"])->name("productDetail");
Route::post("/newsletter/subscribe",[FrontendController::class,"Newsletter"])->name("newsletter");

Route::get("/cart",[CartController::class,"index"])->name("cart.index");
Route::post("/cart/add",[CartController::class,"add"])->name("cart.add");
Route::post("/cart/update",[CartController::class,"update"])->name("cart.update");
Route::post("/cart/remove",[CartController::class,"remove"])->name("cart.remove");

Route::get("/checkout",[CheckoutController::class,"index"])->name("checkout");
Route::post("/checkout/order",[CheckoutController::class,"makeOrder"])->name("checkout.order");
Route::get("/thankyou",[CheckoutController::class,"thankyou"])->name("thankyou");

Route::get("/dashboard",function(){
    return Inertia::render("Dashboard");
})->middleware(["auth","verified"])->name("dashboard");

Route::middleware("auth")->group(function(){
    Route::get("change-password",function(){
        return Inertia::render("ChangePassword");
    })->name("change-password");
    Route::post("change-password",[ProfileController::class,"changePassword"])->name("change-password.update");
    Route::get("/profile",[ProfileController::class,"edit"])->name("profile.edit");
    Route::post("/profile",[ProfileController::class,"update"])->name("profile.update");
});

Route::middleware(["auth"])->group(function(){
    Route::group(["middleware" => "role:customer","prefix" => "customer","as" => "customer."],function(){
        Route::get("my-orders",[ProfileController::class,"myOrders"])->name("myOrders");
        Route::get("my-orders/all",[ProfileController::class,"getAllOrders"])->name("myOrders.all");
        Route::get("my-orders/{slug}",[ProfileController::class,"viewOrder"])->name("myOrders.view");
    });
    Route::group(["middleware" => "role:admin","prefix" => "admin","as" => "admin."],function(){
        Route::get("dashboard",[DashboardController::class,"index"])->name("dashboard");
        
        Route::get("users/all",[UserController::class,"getAllUsers"])->name("users.all");
        Route::resource("users",UserController::class,[
            "except" => ["create","store"]
        ]);
  
        Route::get("products/all",[ProductController::class,"getAllProducts"])->name("products.all");
        Route::resource("products",ProductController::class,[
            "except" => ["show"]
        ]);
  
        Route::get("variants/all",[VariantController::class,"getAllVariants"])->name("variants.all");
        Route::resource("variants",VariantController::class,[
            "except" => ["show"]
        ]);
  
        Route::get("variant-details/all",[VariantDetailController::class,"getAllVariantDetails"])->name("variant-details.all");
        Route::resource("variant-details",VariantDetailController::class,[
            "except" => ["show"]
        ]);

        Route::get("orders/all",[OrderController::class,"getAllOrders"])->name("orders.all");
        Route::get("orders/by-user",[OrderController::class,"getOrderByUser"])->name("orders.byUser");
        Route::post("orders/change-status",[OrderController::class,"changeOrderStatus"])->name("orders.status");
        Route::resource("orders",OrderController::class,[
            "only" => ["index","show"]
        ]);
    });
});
require __DIR__."/auth.php";