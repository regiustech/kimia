<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Variant;
use App\Models\VariantDetail;
use Carbon\Carbon;

class ProductVariant extends Model
{
    use HasFactory;
    protected $fillable = ["product_id","variant_id","variant_detail_id","price"];
    protected $hidden = [];
    protected $casts = [
        "product_id" => "integer",
        "variant_id" => "integer",
        "variant_detail_id" => "integer",
        "price" => "float"
    ];
    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->format("d M Y");
    }
    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function variant(){
        return $this->belongsTo(Variant::class);
    }
    public function variantDetail(){
        return $this->belongsTo(VariantDetail::class);
    }
}