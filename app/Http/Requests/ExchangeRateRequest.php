<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExchangeRateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; //  Замените на свою логику авторизации
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'currency' => 'required|string|in:usd,eur,rub', // Допустимые валюты
            'rate' => 'required|numeric|min:0', // Положительное число
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'currency.in' => 'Недопустимая валюта. Допустимы: usd, eur, rub.',
            'rate.min' => 'Курс валюты должен быть положительным числом.',
        ];
    }
}
