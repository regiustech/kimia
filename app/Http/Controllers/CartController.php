<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Cart;
use App\Models\CartItem;

class CartController extends Controller
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
    public function index(Request $request): Response{
        $userId = Auth::user() ? Auth::user()->id : null;
        $sessionId = app("request")->session()->getId();
        $cart = Cart::userSession($userId,$sessionId)->first();
        if(!$cart->shipping_amount){
            $cart->shipping_amount = "4.99";
            $cart->save();
        }
        if(!$cart->tax_percent && $cart->tax_percent != 0){
            $cart->tax_percent = "7.50";
            $cart->save();
        }
        $cart = $this->calcTotal($cart);
        return Inertia::render("Frontend/Cart",compact("cart"));
    }
    public function add(Request $request){
        $productId = $request->product_id;
        $pVariantId = $request->product_variant_id ? $request->product_variant_id : null;
        $quantity = $request->quantity ? $request->quantity : 1;
        $userId = Auth::user() ? Auth::user()->id : null;
        $sessionId = app("request")->session()->getId();
        $cart = Cart::userSession($userId,$sessionId)->first();
        if(!$cart){
            $cart = new Cart();
            $cart->shipping_amount = "4.99";
            $cart->tax_percent = "7.50";
        }
        $cart->user_id = $userId;
        $cart->session_id = $sessionId;
        $cart->save();
        $cartItem = CartItem::where(["cart_id" => $cart->id,"product_id" => $productId,"product_variant_id" => $pVariantId])->first();
        if(!$cartItem){
            $cartItem = new CartItem(["cart_id" => $cart->id,"product_id" => $productId,"product_variant_id" => $pVariantId,"quantity" => $quantity]);
        }else{
            $cartItem->quantity = ((int)$cartItem->quantity + (int)$quantity);
        }
        $cartItem->save();
        $itemCount = $cart->cartItems()->sum("quantity");
        return json_encode(["status" => 200,"message" => "Product Added Sucessfully.","itemCount" => $itemCount]);
    }
    public function update(Request $request){
        $cartId = $request->cart_id;
        $items = $request->items ? json_decode($request->items) : [];
        $cart = Cart::where("id",$cartId)->first();
        if(!$cart){
            return json_encode(["status" => 412,"message" => "Cart not found."]);
        }
        foreach($items as $item){
            $cartItem = CartItem::where(["cart_id" => $cartId,"product_id" => $item->product_id,"product_variant_id" => $item->product_variant_id])->first();
            if($cartItem){
                $cartItem->quantity = $item->quantity;
                $cartItem->save();
            }
        }
        $cart = $this->calcTotal($cart);
        $itemCount = $cart->cartItems()->sum("quantity");
        return json_encode(["status" => 200,"message" => "Cart Updated Sucessfully.","cart" => $cart,"itemCount" => $itemCount]);
    }
    public function updateTax(Request $request){
        $cartId = $request->cart_id;
        $cart = Cart::where("id",$cartId)->first();
        if(!$cart){
            return json_encode(["status" => 412,"message" => "Cart not found."]);
        }
        $cart->tax_percent = $request->tax_percent ? number_format($request->tax_percent,2) : 0;
        $cart->save();
        $cart = $this->calcTotal($cart);
        $itemCount = $cart->cartItems()->sum("quantity");
        return json_encode(["status" => 200,"message" => "Tax Updated Sucessfully.","cart" => $cart,"itemCount" => $itemCount]);
    }
    public function remove(Request $request){
        $cartId = null;
        $itemId = $request->item_id;
        $cartItem = CartItem::where("id",$itemId)->first();
        if($cartItem){
            $cartId = $cartItem->cart_id;
            $cartItem->delete();
        }
        $cart = Cart::where("id",$cartId)->first();
        $cart = $this->calcTotal($cart);
        $itemCount = $cart->cartItems()->sum("quantity");
        return json_encode(["status" => 200,"message" => "Item removed Sucessfully.","cart" => $cart,"itemCount" => $itemCount]);
    }
}