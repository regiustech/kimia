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
        $cartItems = $cart->cartItems()->with("product")->get();
        if(count($cartItems)){
            foreach($cartItems as $cartItem){
                $subtotal += ((float)$cartItem->product->price * (int)$cartItem->quantity);
            }
            $total = ((float)$subtotal + (float)$tax);
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
        $cart = $this->calcTotal($cart);
        return Inertia::render("Frontend/Cart",compact("cart"));
    }
    public function add(Request $request){
        $productId = $request->product_id;
        $quantity = $request->quantity ? $request->quantity : 1;
        $userId = Auth::user() ? Auth::user()->id : null;
        $sessionId = app("request")->session()->getId();
        $cart = Cart::userSession($userId,$sessionId)->first();
        if(!$cart){
            $cart = new Cart();
        }
        $cart->user_id = $userId;
        $cart->session_id = $sessionId;
        $cart->save();
        $cartItem = CartItem::where(["cart_id" => $cart->id,"product_id" => $productId])->first();
        if(!$cartItem){
            $cartItem = new CartItem(["cart_id" => $cart->id,"product_id" => $productId,"quantity" => $quantity]);
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
            $cartItem = CartItem::where(["cart_id" => $cartId,"product_id" => $item->product_id])->first();
            if($cartItem){
                $cartItem->quantity = $item->quantity;
                $cartItem->save();
            }
        }
        $cart = $this->calcTotal($cart);
        $itemCount = $cart->cartItems()->sum("quantity");
        return json_encode(["status" => 200,"message" => "Cart Updated Sucessfully.","cart" => $cart,"itemCount" => $itemCount]);
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