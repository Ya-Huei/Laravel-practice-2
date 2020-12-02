<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\RoleNames;

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
        if (auth()->user()->hasRole(RoleNames::ADMIN)) {
            return $this->adminRules();
        }

        if (auth()->user()->hasRole(RoleNames::FIRM)) {
            return $this->firmRules();
        }

        return $this->commonRules();
    }

    /**
     * Get admin validation rules that apply to the request.
     *
     * @return array
     */
    private function adminRules()
    {
        return [
            'name'       => 'required|string|between:4,256',
            'email'      => 'required|email|max:256',
            'password'   => 'required|string|between:6,32|confirmed',
            'country'    => 'required_with_all:region,city',
            'region'     => 'required_with_all:country,city',
            'city'       => 'required_with_all:country,region',
            'firm'       => 'nullable|string',
            'roles'      => 'nullable|array',
        ];
    }

    /**
     * Get firm validation rules that apply to the request.
     *
     * @return array
     */
    private function firmRules()
    {
        return [
            'name'       => 'required|string|between:4,256',
            'email'      => 'required|email|max:256',
            'password'   => 'required|string|between:6,32|confirmed',
            'country'    => 'required_with_all:region,city',
            'region'     => 'required_with_all:country,city',
            'city'       => 'required_with_all:country,region',
            'roles'      => 'nullable|array',
        ];
    }

    /**
     * Get common validation rules that apply to the request.
     *
     * @return array
     */
    private function commonRules()
    {
        return  [
            'name'       => 'required|string|between:4,256',
            'email'      => 'required|email|max:256',
            'password'   => 'required|string|between:6,32|confirmed',
        ];
    }
}
