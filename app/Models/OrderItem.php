<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\Product;

class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = ["order_id","product_id","price","quantity","total"];
    protected $hidden = [];
    protected $casts = [
        "price" => "float",
        "total" => "float"
    ];
    public function order(){
        return $this->belongsTo(Order::class);
    }
    public function product(){
        return $this->belongsTo(Product::class)->select("id","name","slug","category","catalog_number","price","image");
    }
    public function getCreatedAtAttribute($value){
        return \Carbon\Carbon::parse($value)->format("d M Y");
    }
}