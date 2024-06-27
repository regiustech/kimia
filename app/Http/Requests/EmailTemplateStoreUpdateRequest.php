<?php
namespace App\Http\Requests;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class EmailTemplateStoreUpdateRequest extends FormRequest
{
    public function authorize(){
        return true;
    }
    public function rules(){
        return [
            'subject' => 'required|string|max:255',
            'email_content' => 'required|string'
        ];
    }
}