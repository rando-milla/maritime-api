<?php
namespace App\Http\Requests\VesselOpex;

use Illuminate\Foundation\Http\FormRequest;

class CreateVesselOpexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => 'required|date',
            'expenses' => 'required|numeric|min:0',
        ];
    }
}
