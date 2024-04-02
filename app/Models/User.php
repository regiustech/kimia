<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Cart;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens,HasFactory,Notifiable,SoftDeletes;
    protected $fillable = ["first_name","last_name","email","phone","password","street_address","city","state","zipcode","country"];
    protected $casts = [
        "email_verified_at" => "datetime",
        "password" => "hashed"
    ];
    public function scopeCustomer($query){
        return $query->where("role","customer");
    }
    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->format("d M Y");
    }
    public function cart(){
        return $this->hasOne(Cart::class);
    }
}