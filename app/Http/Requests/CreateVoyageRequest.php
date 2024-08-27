<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateVoyageRequest extends FormRequest
{
    public function authorize()
    {
        return true; // You can implement authorization logic here if needed
    }

    public function rules()
    {
        return [
            'vessel_id' => 'required|exists:vessels,id',
            'start' => 'required|date',
            'end' => 'nullable|date|after:start',
            'revenues' => 'nullable|numeric|min:0',
            'expenses' => 'nullable|numeric|min:0',
        ];
    }
}
