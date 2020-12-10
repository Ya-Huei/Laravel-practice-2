<?php

namespace App\Http\Requests\Devices;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\RoleNames;

class DeviceUpdateFormValidation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->user()->hasRole(RoleNames::ADMIN)) {
            return true;
        }
        if (auth()->user()->hasRole(RoleNames::FIRM) &&
            auth()->user()->firm_id === $this->device->firm_id) {
            return true;
        }
        if (auth()->user()->hasRole(RoleNames::LOCATION) &&
            auth()->user()->firm_id === $this->device->firm_id &&
            auth()->user()->location_id === $this->device->location_id) {
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
            'country'    => 'required_with_all:region,city|required_if:status,Enable',
            'region'     => 'required_with_all:country,city',
            'city'       => 'required_with_all:country,region',
            'address'    => 'nullable|string|between:0,255',
            'firm'       => 'nullable|integer',
            'status'     => 'required|integer',
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
            'country'    => 'required_with_all:region,city|required_if:status,Enable',
            'region'     => 'required_with_all:country,city',
            'city'       => 'required_with_all:country,region',
            'address'    => 'nullable|string|between:0,255',
            'status'     => 'required|integer',
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
            'address'    => 'nullable|string|between:0,255',
            'status'     => 'required|integer',
        ];
    }
}
