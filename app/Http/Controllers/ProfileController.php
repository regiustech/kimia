<?php
namespace App\Http\Controllers;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;
use App\Models\Order;

class ProfileController extends Controller
{
    public function edit(Request $request): Response{
        $user = $request->user();
        return Inertia::render("Profile",compact("user"));
    }
    public function update(Request $request){
        $user = $request->user();
        $emailExists = User::where("email",$request->email)->where("id","!=",$user->id)->first();
        if($emailExists){
            return json_encode(["status" => 419,"message" => "Email address already in used."]);
        }
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->company = $request->company ? $request->company : null;
        $user->phone = $request->phone ? $request->phone : null;
        $user->street_address = $request->street_address ? $request->street_address : null;
        $user->city = $request->city ? $request->city : null;
        $user->state = $request->state ? $request->state : null;
        $user->zipcode = $request->zipcode ? $request->zipcode : null;
        $user->country = $request->country ? $request->country : null;
        $user->save();
        return json_encode(["status" => 200,"message" => "Profile updated successfully."]);
    }
    public function changePassword(Request $request){
        $user = User::findOrFail($request->user()->id);
        if(!Hash::check($request->old_password,$user->password)){
            return json_encode(["status" => 419,"message" => "Current password does not match"]);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        return json_encode(["status" => 200,"message" => "Password change successfully."]);
    }
    public function myOrders(){
        return Inertia::render('MyOrders');
    }
    public function getAllOrders(Request $request){
        $user = $request->user();
        $length = $request->length ? $request->length : 10;
        $orders = Order::where("user_id",$user->id)->orderBy("id","desc");
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
    public function viewOrder(Request $request,$slug){
        $user = $request->user();
        $order = Order::where(["user_id" => $user->id,"slug" => $slug])->first();
        if(!$order){
            return Redirect::route("customer.myOrders");
        }
        $orderItems = $order->orderItems()->with("product","variantDetail")->get();
        return Inertia::render("ViewOrder",compact("order","orderItems"));
    }
}