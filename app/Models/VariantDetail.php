<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Variant;
use Carbon\Carbon;

class VariantDetail extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ["variant_id","name","order_no"];
    protected $hidden = [];
    protected $casts = [
        "variant_id" => "integer",
        "order_no" => "integer"
    ];
    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->format("d M Y");
    }
    public function variant(){
        return $this->belongsTo(Variant::class);
    }
}