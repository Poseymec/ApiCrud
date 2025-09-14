<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProduitRequest extends FormRequest
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
       // $id=$this->route('produit');  //recupere l'id du produit a modifier
        return [
            'name' => [
                'required',
                Rule::unique('produits', 'name')->ignore($this->produit?->id),
            ],
            'categorie_id' => 'required|exists:categories,id',
            'description1' => 'nullable|string|max:255',
            'description2' => 'required|string|max:5000',
            'prix'         => 'required|numeric|min:0|max:999999.99',
            'images.*'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cover'        => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status'       => 'in:active,inactive',

        ];
    }
}
