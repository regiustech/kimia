<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\OrderItem;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = [];
    protected $hidden = [];
    protected $casts = [
        "subtotal" => "float",
        "tax" => "float",
        "total" => "float"
    ];
    public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }
    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->format("d M Y");
    }
}