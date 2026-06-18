<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string'],
            'phone' => ['required', 'string', 'regex:/^09\d{9}$/'],
            'city_id' => ['required', 'integer'],
            'province_id' => ['required', 'integer'],
            'address' => ['required', 'string'],
            'prd_id' => ['required', 'integer', 'exists:products,id'],
            'prd_qty' => ['required', 'integer', 'min:1'],
            'shipping_method' => ['required', 'string'],
            'images' => ['required', 'array'],
            'images.*' => ['image', 'max:5120'],
        ];
    }
}
