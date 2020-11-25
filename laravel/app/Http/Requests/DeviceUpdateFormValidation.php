<?php

namespace App\Http\Requests;

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
            'country'    => 'required_with_all:region,city|required_if:status,Enable',
            'region'     => 'required_with_all:country,city',
            'city'       => 'required_with_all:country,region',
            'address'    => 'nullable|string|between:0,255',
            'firm'       => 'nullable|string',
            'status'     => 'string',
        ];
    }
}
