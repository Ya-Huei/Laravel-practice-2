<?php

namespace App\Http\Requests\Otas;

use Illuminate\Foundation\Http\FormRequest;

class DeviceUpdateOtaFormValidation extends FormRequest
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
            'type'      => 'required|integer|required_with:name',
            'name'      => 'required|integer|required_with:type',
            'devices'   => 'required|array',
        ];
    }
}
