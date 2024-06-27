<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EmailTemplate extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $hidden = [];
    protected $casts = [
        "subject" => "string",
        "type" => "string",
        "email_content" => "string"
    ];
    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->format("d M Y");
    }
}