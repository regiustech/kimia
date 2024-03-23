<?php
namespace App\Http\Requests;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    public function authorize(){
        return true;
    }
    public function rules(){
        return [
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:30',
            'catalog_number' => 'required|string|max:30',
            'cas_number' => 'required|string|max:30',
            'price' => 'required|numeric|between:0,9999.99'
        ];
    }
}