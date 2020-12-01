<?php

namespace App\Http\Requests\Users;

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
        if ($this->user->name == "admin") {
            return false;
        }
        if (auth()->user()->hasRole('admin')) {
            return true;
        }
        if (auth()->user()->firm_id === $this->user->firm_id) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rule = [
            'password'   => 'nullable|string|between:6,32|confirmed',
            'country'    => 'required_with_all:region,city',
            'region'     => 'required_with_all:country,city',
            'city'       => 'required_with_all:country,region',
            'roles'      => 'nullable|array',
        ];

        if (auth()->user()->hasRole('admin')) {
            $rule += ['firm' => 'nullable|string'];
        }

        return $rule;
    }
}
