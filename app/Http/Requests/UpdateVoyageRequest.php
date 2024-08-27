<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVoyageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'start' => 'sometimes|required|date',
            'end' => 'sometimes|nullable|date|after:start',
            'revenues' => 'sometimes|nullable|numeric|min:0',
            'expenses' => 'sometimes|nullable|numeric|min:0',
            'status' => 'sometimes|required|in:pending,ongoing,submitted',
        ];
    }
}
