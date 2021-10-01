<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegistrationRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:users,email,' . request()->id . ',id',
            'birthday' => 'required',
            'address' => 'required',
            'city' => 'required',
            'country_id' => 'required',
            'region' => 'required',
        ];

        if (request()->isMethod('post')) {
            $rule['password'] = 'required|confirmed|min:6';
        } else if(request()->isMethod('put')) {
            $rule['password'] = 'confirmed|nullable|min:6';
        }

        return $rule;
    }
}
