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
        return false;
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
            'sku' => 'required',
            'url' => 'required',
            'location' => 'required',
            'ean' => 'nullable|string',
            'jan' => 'nullable|string',
            'mpn' => 'nullable|string',
            'upc' => 'nullable|string',
            'discount_price' => 'numeric|required',
            'price' => 'numeric|required',
            'stock_status' => 'required',
            'manufacture' => 'required',
            'description' => 'required',
            'attributes' => 'required',
            'images' => 'required',
            'date_added' => 'required',
            'date_parsing' => 'required',
            'date_modified' => 'required',
            'date_parsing' => 'required',
            'quantity' => 'numeric|required',
            'status' => 'nullable|numeric',
        ];
    }
}
