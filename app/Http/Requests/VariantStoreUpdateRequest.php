<?php
namespace App\Http\Requests;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class VariantStoreUpdateRequest extends FormRequest
{
    public function authorize(){
        return true;
    }
    public function rules(){
        return [
            'name' => 'required|string|max:255'
        ];
    }
}