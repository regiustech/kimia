<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\OrderItem;

class Order extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ["slug","name","email","phone","company","country","street_address","city","state","zipcode","additional_notes","subtotal","tax","total"];
    protected $hidden = [];
    protected $casts = [
        "subtotal" => "float",
        "tax" => "float",
        "total" => "float"
    ];
    public function orderItems(){
        return $this->hasMany(OrderItem::class)->select("id","product_id","price","quantity","total");
    }
    public function getCreatedAtAttribute($value){
        return \Carbon\Carbon::parse($value)->format("d M Y");
    }
}