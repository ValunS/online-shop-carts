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
            'usd' => 'required|numeric',
            'eur' => 'required|numeric',
            'rub' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return
            [
            'usd.required' => 'Необходимо ввести курс доллара',
            'usd.numeric' => 'Курс доллара должен быть числом',

            'eur.required' => 'Необходимо ввести курс евро',
            'eur.numeric' => 'Курс евро должен быть числом',

            'rub.required' => 'Необходимо ввести курс рубля',
            'rub.numeric' => 'Курс рубля должен быть числом',

        ];
    }
}
