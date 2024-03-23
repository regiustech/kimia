<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cart;
use App\Models\Product;

class CartItem extends Model
{
    use HasFactory;
    protected $fillable = ["cart_id","product_id","quantity"];
    protected $hidden = [];
    protected $casts = [];
    public function getCreatedAtAttribute($value){
        return \Carbon\Carbon::parse($value)->format("d M Y");
    }
    public function cart(){
        return $this->belongsTo(Cart::class);
    }
    public function product(){
        return $this->belongsTo(Product::class)->select("id","name","slug","category","catalog_number","price","image");
    }
}