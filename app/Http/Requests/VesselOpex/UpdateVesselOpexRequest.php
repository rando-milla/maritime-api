<?php
namespace App\Http\Requests\VesselOpex;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVesselOpexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => 'sometimes|required|date',
            'expenses' => 'sometimes|required|numeric|min:0',
        ];
    }
}
