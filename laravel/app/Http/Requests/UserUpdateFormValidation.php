<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateFormValidation extends FormRequest
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
            'password'   => 'nullable|string|between:6,32|confirmed',
            'country'    => 'required_with_all:region,city|string',
            'region'     => 'required_with_all:country,city|string',
            'city'       => 'required_with_all:country,region|string',
            'firm'       => 'nullable|string',
            'roles'      => 'nullable|array',
        ];
    }
}
