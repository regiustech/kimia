<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Models\Order;
use App\Models\OrderItem;
use App\Jobs\OrderChangeStatusEmailJob;

class OrderController extends Controller
{
    public function index(){
        return Inertia::render("Admin/Orders/Index");
    }
    public function getAllOrders(Request $request){
        $length = $request->length ? $request->length : 10;
        $orders = Order::orderBy("id","desc");
        if($length > 0){
            $output = $orders->paginate($length);
        }else{
            $orders = $orders->get();
            $output = [
                "current_page" => 1,
                "data" => $orders,
                "from" => 1,
                "to" => count($orders),
                "total" => count($orders),
                "last_page" => 1
            ];
        }
        return json_encode($output);
    }
    public function getOrderByUser(Request $request){
        $length = $request->length ? $request->length : 10;
        $userId = $request->user_id ? $request->user_id : null;
        $orders = Order::orderBy("id","desc")->where("user_id",$userId);
        if($length > 0){
            $output = $orders->paginate($length);
        }else{
            $orders = $orders->get();
            $output = [
                "current_page" => 1,
                "data" => $orders,
                "from" => 1,
                "to" => count($orders),
                "total" => count($orders),
                "last_page" => 1
            ];
        }
        return json_encode($output);
    }
    public function show(Order $order){
        $orderItems = $order->orderItems()->with("product","variantDetail")->get();
        return Inertia::render("Admin/Orders/View",compact("order","orderItems"));
    }
    public function changeOrderStatus(Request $request){
        $orderId = $request->order_id;
        $order = Order::where("id",$orderId)->first();
        if(!$order){
            return json_encode(["status" => 419,"message" => "Order Not Found."]);
        }
        if($order->order_status == "cancelled"){
            return json_encode(["status" => 419,"message" => "Order is cancelled."]);
        }
        if($order->order_status == "completed"){
            return json_encode(["status" => 419,"message" => "Order is completed."]);
        }
        $order->order_status = $request->order_status;
        $order->save();
        $data = [
            "to_address" => $order->billing_email,
            "subject" => env("APP_NAME")." - Order #".$order->id." notification",
            "name" => $order->billing_name,
            "order_message" => ""
        ];
        if($order->order_status == "processing"){
            $data["order_message"] = "We wanted to inform you that your order is currently being processed. We appreciate your patience.";
        }else if($order->order_status == "hold"){
            $data["order_message"] = "We regret to inform you that your order is currently on hold. Our team is working to resolve this promptly. We apologize for any inconvenience caused and appreciate your patience.";
        }else if($order->order_status == "unholed"){
            $data["order_message"] = "We're pleased to inform you that your order is now off hold and processing is back on track. Thank you for your patience.";
        }else if($order->order_status == "completed"){
            $data["order_message"] = "We're pleased to inform you that your order has been successfully completed! Thank you for choosing us.";
        }else if($order->order_status == "cancelled"){
            $data["order_message"] = "We regret to inform you that your order has been cancelled. We apologize for any inconvenience this may have caused. If you have any questions, please don't hesitate to reach out to us.";
        }
        if($order->user_id){
            $user = User::where("id",$order->user_id)->first();
            if($user){
                $data["to_address"] = $user->email;
                $data["name"] = $user->first_name;
            }
        }
        OrderChangeStatusEmailJob::dispatch($data);
        return json_encode(["status" => 200,"message" => "Order status changed successfully."]);
    }
}