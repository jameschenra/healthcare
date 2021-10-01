<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use App\Common\Utils;
use Illuminate\Foundation\Http\FormRequest;

class MemberRequest extends FormRequest
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
            'birthday' => 'required',
            'address' => 'required',
            'city' => 'required',
            'country_id' => 'required',
            'region' => 'required',
            'relationship' => 'required',
            'plan_type' => 'required'
        ];

        // check if adult or child
        if (Utils::checkAdult(request()->input('birthday'))) {
            $rule = array_merge($rule, [
                'phone' => 'required',
                'email' => 'required|email|unique:users,email,' . request()->user_id . ',id'
            ]);

            if (request()->isMethod('post')) {
                $rule['password'] = 'required|confirmed|min:6';
            } else if(request()->isMethod('put')) {
                $rule['password'] = 'confirmed|nullable|min:6';
            }
        }

        /* if (request()->isMethod('post')) {
            $rule["password"] = "required|confirmed|min:6";
        } else if(request()->isMethod('put')) {
            $rule["password"] = "confirmed|nullable|min:6";
        } */

        
        return $rule;
    }
}
