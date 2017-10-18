<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password'=>'required|min:6|confirmed',
            'password_confirmation'=>'required'
        ];
    }
    public function messages()
    {
        return [
            'password.required'=>'请输入密码',
            'password.min'=>'密码最少6位',
            'password.confirmed'=>'两次密码输入不一致',
            'password_confirmation.required'=>'请输入确认密码'
        ];

    }
}
