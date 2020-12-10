<?php

namespace App\Http\Requests\Repairs;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\RoleNames;

class RepairUpdateFormValidation extends FormRequest
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
            auth()->user()->firm_id === $this->repair->device->firm_id) {
            return true;
        }
        if (auth()->user()->hasRole(RoleNames::LOCATION) &&
            auth()->user()->firm_id === $this->repair->device->firm_id &&
            auth()->user()->location_id === $this->repair->device->location_id) {
            return true;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'reason' => 'required|string',
            'worker' => 'nullable|string',
            'status' => 'required|integer',
        ];
    }
}
