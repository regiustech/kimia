<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\EmailTemplate;
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
                $orderData = [
                    "to_address" => (Auth::user() ? Auth::user()->email : $order->billing_email),
                    "name" => (Auth::user() ? Auth::user()->first_name : $order->billing_name)
                ];
                $data = $this->prepareOrderMail($order,$orderData,"customer");
                OrderConfirmEmailJob::dispatch($data);
                $adminOrderData = [
                    "to_address" => env("SALES_EMAIL_ADDRESS"),
                    "user_name" => (Auth::user() ? Auth::user()->first_name : $order->billing_name)
                ];
                $adminData = $this->prepareOrderMail($order,$adminOrderData,"admin");
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
    private function prepareOrderMail($order,$data,$type = "customer"){
        $emailTemplate = EmailTemplate::where("type","new_order")->first();
        if($emailTemplate){
            if($type = "customer"){
                $subject = str_replace("%site_title%","Kimia Corp.",$emailTemplate->subject);
                $subject = str_replace("%site_url%","https://kimiacorp.com",$subject);
                $subject = str_replace("%name%",$data['name'],$subject);
                $subject = str_replace("%order_id%",$order->id,$subject);
                $subject = str_replace("%order_date%",$order->created_at,$subject);
                $subject = str_replace("<p",'<p style="margin:0;"',$subject);
            }else if($type = "admin"){
                $subject = "Kimia Corp. - Order #" . $order->id . " placed on website";
            }
            $content = str_replace("%site_title%","Kimia Corp.",$emailTemplate->email_content);
            $content = str_replace("%site_url%","https://kimiacorp.com",$content);
            if($type = "customer"){
                $content = str_replace("%name%",$data['name'],$content);
            }else if($type = "admin"){
                $content = str_replace("%name%","Admin,<br/>".$data['user_name']." has been placed an order. Below are the details of order:",$content);
            }
            $billingAddress = $order->billing_street_address . "<br/>" . $order->billing_city . ", " . $order->billing_state . " " . $order->billing_zipcode;
            $shippingAddress = $order->shipping_street_address . "<br/>" . $order->shipping_city . ", " . $order->shipping_state . " " . $order->shipping_zipcode;
            $oHtml = '<table style="width:100%;max-width:800px;border-collapse:collapse;border:1px solid #ddd;" cellspacing="0" cellpadding="0">';
                $oHtml .= '<thead>';
                    $oHtml .= '<tr>';
                        $oHtml .= '<th style="padding:8px;text-align:left;background:#f9ebff;">Product</th>';
                        $oHtml .= '<th style="padding:8px;text-align:left;background:#f9ebff;">Price</th>';
                        $oHtml .= '<th style="padding:8px;text-align:left;background:#f9ebff;">Quantity</th>';
                        $oHtml .= '<th style="padding:8px;text-align:left;background:#f9ebff;">Total</th>';
                    $oHtml .= '</tr>';
                $oHtml .= '</thead>';
                $oHtml .= '<tbody>';
                    if(count($order->orderItems)):
                        foreach($order->orderItems as $orderItem):
                            $oHtml .= '<tr>';
                                $oHtml .= '<td style="padding:8px;text-align:left;font-size:14px;border-bottom:1px solid #ddd;">';
                                    $oHtml .= '<div style="display:flex;align-items:center;column-gap:10px;">';
                                        $oHtml .= '<img src="'.$orderItem->product->image.'" alt="'.$orderItem->product->name.'" style="width:40px;height:40px;display:flex;"/>';
                                        $oHtml .= '<div style="display:flex;flex-direction:column;">';
                                            $oHtml .= '<div style="font-weight:500;font-size:16px;color:#3A3A3A;line-height:26px;text-align:left;">'.$orderItem->product->name.'</div>';
                                            $oHtml .= '<div style="font-weight:500;font-size:16px;color:#3A3A3A;line-height:26px;text-align:left;">'.$orderItem->product->catalog_number.'</div>';
                                        $oHtml .= '</div>';
                                    $oHtml .= '</div>';
                                $oHtml .= '</td>';
                                $oHtml .= '<td style="padding:8px;text-align:left;font-size:14px;border-bottom:1px solid #ddd;">'.$orderItem->price.'</td>';
                                $oHtml .= '<td style="padding:8px;text-align:left;font-size:14px;border-bottom:1px solid #ddd;">'.$orderItem->quantity.'</td>';
                                $oHtml .= '<td style="padding:8px;text-align:left;font-size:14px;border-bottom:1px solid #ddd;">'.$orderItem->total.'</td>';
                            $oHtml .= '</tr>';
                        endforeach;
                    endif;
                $oHtml .= '</tbody>';
                $oHtml .= '<tfoot>';
                    $oHtml .= '<tr>';
                        $oHtml .= '<td colspan="2"></td>';
                        $oHtml .= '<td style="text-align:right;padding:8px;font-size:14px;">Subtotal:</td>';
                        $oHtml .= '<td style="padding:8px;font-size:14px;">$'.$order->subtotal.'</td>';
                    $oHtml .= '</tr>';
                    $oHtml .= '<tr>';
                        $oHtml .= '<td colspan="2"></td>';
                        $oHtml .= '<td style="text-align:right;padding:8px;font-size:14px;border-top:1px solid #ddd;">Shipping:</td>';
                        $oHtml .= '<td style="padding:8px;font-size:14px;border-top:1px solid #ddd;">$'.$order->shipping_amount.'</td>';
                    $oHtml .= '</tr>';
                    $oHtml .= '<tr>';
                        $oHtml .= '<td colspan="2"></td>';
                        $oHtml .= '<td style="text-align:right;padding:8px;font-size:14px;border-top:1px solid #ddd;">Tax:</td>';
                        $oHtml .= '<td style="padding:8px;font-size:14px;border-top:1px solid #ddd;">$'.$order->tax.'</td>';
                    $oHtml .= '</tr>';
                    $oHtml .= '<tr>';
                        $oHtml .= '<td colspan="2"></td>';
                        $oHtml .= '<td style="text-align:right;font-size:15px;padding:8px;border-top:1px solid #ddd;font-weight:600;">Order Total:</td>';
                        $oHtml .= '<td style="padding:8px;font-size:15px;border-top:1px solid #ddd;font-weight:600;">$'.$order->total.'</td>';
                    $oHtml .= '</tr>';
                $oHtml .= '</tfoot>';
            $oHtml .= '</table>';
            $content = str_replace("%order_id%",$order->id,$content);
            $content = str_replace("%order_date%",$order->created_at,$content);
            $content = str_replace("%billing_name%",$order->billing_name,$content);
            $content = str_replace("%billing_address%",$billingAddress,$content);
            $content = str_replace("%shipping_name%",$order->shipping_name,$content);
            $content = str_replace("%shipping_address%",$shippingAddress,$content);
            $content = str_replace("%order_items%",$oHtml,$content);
            if($type == "customer" && $order->send_invoice_me){
                $content = str_replace("%is_send_invoice%","You will receive an invoice from Kimia to complete the payment for this purchase.",$content);
            }
            $content = str_replace("<p",'<p style="margin:0;"',$content);
            $data['content'] = $content;
            $data['subject'] = $subject;
            return $data;
        }
        return;
    }
    public function thankyou(Request $request){
        return Inertia::render("Frontend/Thankyou");
    }
}