<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category' => 'required',
            'name' => 'required',
            'model' => 'required',
            'sku' => 'nullable|string',
            'url' => 'required',
            'location' => 'required',
            'ean' => 'nullable|string',
            'jan' => 'nullable|string',
            'mpn' => 'nullable|string',
            'upc' => 'nullable|string',
            'discount_price' => 'required|numeric',
            'price' => 'required|numeric',
            'stock_status' => 'boolean',
            'manufacturer' => 'required',
            'description' => 'required',
            'attributes' => 'required',
            'images' => 'required',
            'quantity' => 'required|numeric',
            'status' => 'boolean',
        ];
    }
}
