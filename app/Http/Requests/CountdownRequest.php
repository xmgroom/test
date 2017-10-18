<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CountdownRequest extends FormRequest
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
            'countdown_text'=>'required|max:10',
            'countdown_time'=>'required'
        ];
    }

    public function messages()
    {
        return [
            'countdown_text.required'=>'请输入提示文字',
            'countdown_text.max'=>'提示文字不能超过10个字符',
            'countdown_time.required'=>'请输入直播开始时间'
        ];
    }
}
