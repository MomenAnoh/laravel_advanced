<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProduct extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'=>['nullable'],
            'des'      => ['required'],
            'image'    => ['nullable', 'max:2048'],
            'price'    => ['nullable', 'integer'],
            'quantity' => ['nullable', 'integer'],
        ];
    }
}
