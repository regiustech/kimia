<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use Carbon\Carbon;

class CartItem extends Model
{
    use HasFactory;
    protected $fillable = ["cart_id","product_id","product_variant_id","quantity"];
    protected $hidden = [];
    protected $casts = [];
    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->format("d M Y");
    }
    public function cart(){
        return $this->belongsTo(Cart::class);
    }
    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function productVariant(){
        return $this->belongsTo(ProductVariant::class);
    }
}