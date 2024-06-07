<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Jobs\OrderConfirmEmailJob;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    public function calcTotal($cart){
        if(!$cart){
            return $cart;
        }
        $subtotal = $tax = $total = 0;
        $cartItems = $cart->cartItems()->with("product","productVariant")->get();
        if(count($cartItems)){
            foreach($cartItems as $cartItem){
                if($cartItem->product->product_type == "regular"){
                    $subtotal += ((float)$cartItem->product->price * (int)$cartItem->quantity);
                }else if($cartItem->product->product_type == "variant"){
                    $subtotal += ((float)$cartItem->productVariant->price * (int)$cartItem->quantity);
                }
            }
            if($cart->tax_percent > 0){
                $tax = (((float)$subtotal * (float)$cart->tax_percent)/100);
            }
            $total = ((float)$subtotal + (float)$cart->shipping_amount + (float)$tax);
        }
        $cart->cartItems = $cartItems;
        $cart->subtotal = $subtotal;
        $cart->tax = $tax;
        $cart->total = $total;
        return $cart;
    }
    public function index(Request $request){
        $userId = Auth::user() ? Auth::user()->id : null;
        $sessionId = app("request")->session()->getId();
        $cart = Cart::userSession($userId,$sessionId)->first();
        $cart = $this->calcTotal($cart);
        if(!$cart || ($cart && $cart->cartItems && count($cart->cartItems) < 1)){
            return Redirect::route("cart.index")->with("error","Cart is Empty");
        }
        return Inertia::render("Frontend/Checkout",compact("cart"));
    }
    public function makeOrder(Request $request){
        $userId = Auth::user() ? Auth::user()->id : null;
        $sessionId = app("request")->session()->getId();
        $cart = Cart::userSession($userId,$sessionId)->first();
        $cart = $this->calcTotal($cart);
        if(!$cart){
            return Redirect::route("cart.index")->with("error","Cart is Empty");
        }
        $cart->tax = number_format($cart->tax,2);
        $cart->total = number_format($cart->total,2);
        try{
            if($request->invoice == "1"){
                $order = $this->insertOrder($userId,$cart,$request);
            }else{
                $stripe = new \Stripe\StripeClient(env("STRIPE_SECRET_KEY"));
                $charge = $stripe->charges->create([
                    "amount" => ((float)$cart->total * 100),
                    "currency" => "usd",
                    "source" => $request->token,
                    "description" => "Order Payment " . ((float)$cart->total)
                ]);
                $order = $this->insertOrder($userId,$cart,$request,$charge);
            }
            if($order && $order->id){
                $data = [
                    "to_address" => (Auth::user() ? Auth::user()->email : $order->billing_email),
                    "subject" => "Kimia Corp. - Your Order #" . $order->id . " has been placed",
                    "name" => (Auth::user() ? Auth::user()->first_name : $order->billing_name),
                    "order" => $order,
                    "type" => "customer"
                ];
                OrderConfirmEmailJob::dispatch($data);
                $adminData = [
                    "to_address" => env("SALES_EMAIL_ADDRESS"),
                    "subject" => "Kimia Corp. - Order #" . $order->id . " placed on website",
                    "name" => "Admin",
                    "order" => $order,
                    "type" => "admin",
                    "user_name" => $data["name"]
                ];
                OrderConfirmEmailJob::dispatch($adminData);
            }
            return Redirect::route("thankyou")->with("success","Order Placed Sucessfully.");
        }catch(\Exception $e){
            return Redirect::route("cart.index")->with("error",$e->getMessage());
        }
    }
    private function insertOrder($userId,$cart,$request,$charge = null){
        $order = new Order();
        if($userId){
            $order->user_id = $userId;
        }
        $slug = "o" . Carbon::now()->format("mdY") . rand(000000,999999);
        $order->slug = $slug;
        $order->billing_name = $request->billing_name;
        $order->billing_email = $request->billing_email;
        $order->billing_phone = $request->billing_phone;
        $order->billing_company = $request->billing_company;
        $order->billing_country = $request->billing_country;
        $order->billing_street_address = $request->billing_street_address;
        $order->billing_city = $request->billing_city;
        $order->billing_state = $request->billing_state;
        $order->billing_zipcode = $request->billing_zipcode;
        $order->shipping_name = ($request->same_as_billing ? $request->billing_name : $request->shipping_name);
        $order->shipping_company = ($request->same_as_billing ? $request->billing_company : $request->shipping_company);
        $order->shipping_country = ($request->same_as_billing ? $request->billing_country : $request->shipping_country);
        $order->shipping_street_address = ($request->same_as_billing ? $request->billing_street_address : $request->shipping_street_address);
        $order->shipping_city = ($request->same_as_billing ? $request->billing_city : $request->shipping_city);
        $order->shipping_state = ($request->same_as_billing ? $request->billing_state : $request->shipping_state);
        $order->shipping_zipcode = ($request->same_as_billing ? $request->billing_zipcode : $request->shipping_zipcode);
        $order->additional_notes = $request->additional_notes;
        if($charge){
            $order->charge_id = $charge->id;
            $order->response = json_encode($charge);
        }
        $order->subtotal = $cart->subtotal;
        $order->shipping_amount = $cart->shipping_amount;
        $order->tax_percent = $cart->tax_percent;
        $order->tax = $cart->tax;
        $order->total = $cart->total;
        $order->send_invoice_me = (($request->invoice == "1") ? true : false);
        $order->save();
        if(count($cart->cartItems)){
            foreach($cart->cartItems as $cartItem){
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $cartItem->product_id;
                $orderItem->variant_detail_id = $cartItem->productVariant && $cartItem->productVariant->variant_detail_id ? $cartItem->productVariant->variant_detail_id : null;
                if($cartItem->product->product_type == "regular"){
                    $orderItem->price = $cartItem->product->price;
                    $orderItem->total = ((float)$cartItem->product->price * $cartItem->quantity);
                }else if($cartItem->product->product_type == "variant"){
                    $orderItem->price = $cartItem->productVariant->price;
                    $orderItem->total = ((float)$cartItem->productVariant->price * $cartItem->quantity);
                }
                $orderItem->quantity = $cartItem->quantity;
                $orderItem->save();
            }
        }
        CartItem::where("cart_id",$cart->id)->delete();
        $cart->delete();
        $order->orderItems = $order->orderItems()->with('product')->get();
        return $order;
    }
    public function thankyou(Request $request){
        return Inertia::render("Frontend/Thankyou");
    }
}