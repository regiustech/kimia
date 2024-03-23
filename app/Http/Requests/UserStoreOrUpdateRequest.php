<?php
namespace App\Http\Requests;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserStoreOrUpdateRequest extends FormRequest
{
    public function authorize(){
        return true;
    }
    public function rules(){
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$this->user_id.',id,deleted_at,NULL'
        ];
    }
}