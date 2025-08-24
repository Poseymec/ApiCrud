<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProduitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'         => 'required|string|max:255|unique:produits,name',
            'categorie_id' => 'required|exists:categories,id',
            'description1' => 'required|string|max:255',
            'description2' => 'required|text|max:1000',
            'prix'         => 'required|numeric|min:0|max:999999.99',
            'images.*'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            
        ];
    }
}
