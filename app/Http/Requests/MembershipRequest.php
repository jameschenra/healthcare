<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class MembershipRequest extends FormRequest
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
            'relationship' => 'required',
            'plan_type' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'birthday' => 'required',
            'address' => 'required',
            'city' => 'required',
            'country_id' => 'required',
            'region' => 'required',
        ];

        $relationship = request()->input('relationship');
        if ($relationship != 'Primary') {
            $rule['primary_id'] = 'required';
        }

        $date = Carbon::parse((now()->year - 18) . '-12-31');

        if ($relationship != 'Son' && $relationship != 'Daughter') {
            $rule['email'] = 'required|email|unique:users,email,' . request()->id . ',id';
            $rule['phone'] = 'required';
            $rule['birthday'] = 'required|date|before:' . $date;

            if (request()->isMethod('post')) {
                $rule['password'] = 'required|confirmed|min:6';
            } else if(request()->isMethod('put')) {
                $rule['password'] = 'confirmed|nullable|min:6';
            }
        } else {
            $rule['birthday'] = 'required|date|after:' . $date;
        }

        return $rule;
    }

    public function messages()
    {
        return [
            'birthday.before' => 'Adult should be over 18 years old.',
            'birthday.after' => 'Child should be under 18 years old.',
        ];
    }
}
