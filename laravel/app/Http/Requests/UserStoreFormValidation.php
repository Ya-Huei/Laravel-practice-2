<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreFormValidation extends FormRequest
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
            'name'       => 'required|string|between:4,256',
            'email'      => 'required|email|max:256',
            'password'   => 'required|string|between:6,32|confirmed',
            'country'    => 'required_with_all:region,city',
            'region'     => 'required_with_all:country,city',
            'city'       => 'required_with_all:country,region',
            'roles'      => 'nullable|array',
        ];

        if (auth()->user()->hasRole('admin')) {
            $rule += ['firm'       => 'nullable|string'];
        }

        return $rule;
    }
}
