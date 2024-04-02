<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Newsletter extends Model
{
    use HasFactory;
    protected $fillable = ["email"];

    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->format("d M Y");
    }
}
