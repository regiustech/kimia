<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ["name","category","catalog_number","cas_number","price","image","description","specifications"];
    protected $hidden = [];
    protected $casts = [
        "price" => "float",
        "specifications" => "array"
    ];
    public function getCreatedAtAttribute($value){
        return \Carbon\Carbon::parse($value)->format("d M Y");
    }
}
