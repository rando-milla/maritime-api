<?php
namespace App\Http\Requests\Vessel;

use Illuminate\Foundation\Http\FormRequest;

class CreateVesselRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'IMO_number' => 'required|string|max:255|unique:vessels,IMO_number',
        ];
    }
}
