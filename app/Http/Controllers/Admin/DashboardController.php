<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response{
        $orderCounts = [];
        $customers = User::customer()->count();
        $products = Product::count();
        $orders = Order::count();
        $totalSales = Order::sum("total");
        $recentOrders = Order::orderBy("id","desc")->limit(3)->get();
        for($i=1;$i<=12;$i++){
            $orderCounts[] = Order::select('id')->whereYear('created_at','=',date("Y"))->whereMonth('created_at','=',$i)->count();
        }
        $graphData = [
            "labels" => ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
            "datasets" => [
                [
                    "label" => "Orders",
                    "backgroundColor" => "#81499d",
                    "data" => $orderCounts,
                    "borderColor" => "#81499d",
                    "tension" => 0.5,
                    "pointRadius" => 5,
                    "fill" => false
                ]
            ]
        ];
        return Inertia::render('Admin/Dashboard',compact("customers","products","orders","totalSales","recentOrders","graphData"));
    }
}