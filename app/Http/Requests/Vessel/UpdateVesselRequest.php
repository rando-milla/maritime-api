<?php
namespace App\Http\Requests\Vessel;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVesselRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'IMO_number' => 'sometimes|required|string|max:255|unique:vessels,IMO_number,' . $this->route('vessel')->id,
        ];
    }
}
