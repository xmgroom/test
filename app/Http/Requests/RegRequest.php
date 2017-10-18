<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegRequest extends FormRequest
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
            'name'=>'required|unique:users',
            'password'=>'required|min:6|confirmed',
            'email'=>'required|unique:users|email',
            'password_confirmation'=>'required'
        ];
    }
    public function messages()
    {
        return [
            'email.unique' => '邮箱已存在',
            'email.email' => '请输入正确的邮箱',
            'email.required'=>'请输入邮箱',
            'name.unique' => '手机号已存在',
            'name.required'=>'请输入手机号',
            'password.required'=>'请输入密码',
            'password.min'=>'密码最少6位数',
            'password.confirmed'=>'两次密码输入不一致',
            'password_confirmation.required'=>'请输入确认密码'
        ];

    }
}
