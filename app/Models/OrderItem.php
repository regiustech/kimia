<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\Product;
use App\Models\VariantDetail;
use Carbon\Carbon;

class OrderItem extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $hidden = [];
    protected $casts = [
        "price" => "float",
        "total" => "float"
    ];
    public function order(){
        return $this->belongsTo(Order::class);
    }
    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function variantDetail(){
        return $this->belongsTo(VariantDetail::class);
    }
    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->format("d M Y");
    }
}