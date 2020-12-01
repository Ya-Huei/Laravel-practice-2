<?php

namespace App\Http\Requests\Devices;

use Illuminate\Foundation\Http\FormRequest;

class DeviceUpdateFormValidation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->user()->hasRole('admin')) {
            return true;
        }
        if (auth()->user()->firm_id === $this->device->firm_id) {
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
            'country'    => 'required_with_all:region,city|required_if:status,Enable',
            'region'     => 'required_with_all:country,city',
            'city'       => 'required_with_all:country,region',
            'address'    => 'nullable|string|between:0,255',
            'status'     => 'required|string',
        ];

        if (auth()->user()->hasRole('admin')) {
            $rule += ['firm' => 'nullable|string'];
        }

        return $rule;
    }
}
