<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CartItem;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = ["user_id","session_id"];
    protected $hidden = [];
    protected $casts = [];

    public function cartItems(){
        return $this->hasMany(CartItem::class)->select("id","product_id","quantity");
    }
    public function scopeUserSession($query,$userId = null,$sessionId = null){
        if($userId){
            return $query->where("user_id",$userId);
        }else if($sessionId){
            return $query->where("session_id",$sessionId);
        }
        return $query;
    }
    public function getCreatedAtAttribute($value){
        return \Carbon\Carbon::parse($value)->format("d M Y");
    }
}