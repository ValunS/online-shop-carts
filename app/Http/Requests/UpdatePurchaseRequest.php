<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseRequest extends FormRequest
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
            'store_id' => 'sometimes|integer|exists:stores,id', // 'sometimes' - поле не обязательно
            'purchase_date' => 'sometimes|date',
            'sum' => 'sometimes|numeric',
            'currency' => 'sometimes|string|in:usd,eur,rub',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg|max:2048',
        ];
    }

    public function messages()
    {
        return
            [
                'store_id.required' => 'Необходимо ввести id магазина',
                'store_id.integer' => 'id  магазина дожно быть числом',
                'store_id.exists' => 'Такого магазина не существует',

                'purchase_date.required' => 'Необходимо ввести дату',
                'purchase_date.date' => 'Некорректный формат даты',

                'sum.required' => 'Необходимо ввести сумму',
                'sum.numeric' => 'Сумма должна быть числом',

                'currency.required' => 'Необходимо ввести валюту',
                'currency.in' => 'Валюта дожна быть usd,eur,rub',

                'document.mimes' => 'Файл должен быть в формате pdf, jpg или jpeg',
                'document.max' => 'Размер файла не должен превышать 2 МБ',
            ];
    }
}