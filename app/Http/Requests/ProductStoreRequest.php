<?php
namespace App\Http\Requests;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    public function authorize(){
        return true;
    }
    public function rules(){
        $rules = [
            'name' => 'required|string|max:255',
            'product_type' => 'required|string',
            'category' => 'required|string|max:30',
            'catalog_number' => 'required|string|max:30',
            'cas_number' => 'required|string|max:30',
            'image' => 'required|mimes:jpg,jpeg,svg,png'
        ];
        if($this->product_type == "regular"){
            $rules["price"] = 'required|numeric|between:0,999999.99';
        }else if($this->product_type == "variant"){
            $rules["productVariants"] = 'required';
        }
        return $rules;
    }
}