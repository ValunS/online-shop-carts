<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:stores',
        ];
    }

    public function messages()
    {
        return
            [
            'name.required' => 'Необходимо ввести название магазина',
            'name.string' => 'Название магазина дожно быть строкой',
            'name.max' => 'Название магазина не должно превышать 255 символов',
            'name.unique' => 'Такой магазин уже существует',

        ];
    }
}
