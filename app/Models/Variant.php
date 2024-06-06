<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\VariantDetail;
use App\Models\ProductVariant;
use Carbon\Carbon;

class Variant extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = [];
    protected $hidden = [];
    protected $casts = [];
    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->format("d M Y");
    }
    public function variantDetails(){
        return $this->hasMany(VariantDetail::class);
    }
    public function productVariants(){
        return $this->belongsTo(ProductVariant::class);
    }
}