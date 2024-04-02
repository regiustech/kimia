<?php
namespace App\Http\Requests;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class VariantDetailStoreUpdateRequest extends FormRequest
{
    public function authorize(){
        return true;
    }
    public function rules(){
        return [
            'variant_id' => 'required',
            'name' => 'required|string|max:255',
            'order_no' => 'required|integer'
        ];
    }
}