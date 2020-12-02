<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\RoleNames;

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
        if (auth()->user()->hasRole(RoleNames::ADMIN)) {
            return true;
        }
        if (auth()->user()->hasRole(RoleNames::FIRM) &&
            !$this->user->hasRole(RoleNames::FIRM) &&
            auth()->user()->firm_id === $this->user->firm_id &&
            $this->user->hasRole(RoleNames::LOCATION)) {
            return true;
        }
        if (auth()->user()->id === $this->user->id) {
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
            'password'   => 'nullable|string|between:6,32|confirmed',
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
            'password'   => 'nullable|string|between:6,32|confirmed',
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
            'password'   => 'nullable|string|between:6,32|confirmed',
            'roles'      => 'nullable|array',
        ];
    }
}
