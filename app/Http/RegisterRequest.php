<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'repassword' => 'required|string|min:8|confirmed',
            'role' => 'required|string',
        ];
    }
}
