<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CartItem;
use Carbon\Carbon;

class Cart extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $hidden = [];
    protected $casts = [];

    public function cartItems(){
        return $this->hasMany(CartItem::class);
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
        return Carbon::parse($value)->format("d M Y");
    }
}