<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteElementCategorieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Utiliser directement l'ID de l'URL au lieu du model binding
        $categoryId = $this->route('site_element_category'); // ou selon le nom de votre paramètre de route

        return [
            'name' => 'required|string|max:255|unique:site_element_categories,name,' . $categoryId,
        ];
    }
}
