<?php

namespace App\Http\Requests\Devices;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Statuses;

class DeviceSaveRepairFormValidation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->device->status_id === Statuses::REPAIR) {
            return false;
        }
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
        return [
            'reason' => 'required|string'
        ];
    }
}
