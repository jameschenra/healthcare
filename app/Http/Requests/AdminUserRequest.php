<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserRequest extends FormRequest
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
        $rule = [
            'email' => 'required|email|unique:admins,email,' . request()->id . ',id'
        ];

        if (request()->isMethod('post')) {
            $rule['password'] = 'required|confirmed|min:6';
        } else if(request()->isMethod('put')) {
            $rule['password'] = 'confirmed|nullable|min:6';
        }

        return $rule;
    }
}
