<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteElementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'site_element_categorie_id' => 'sometimes|exists:site_element_categories,id',
            'name'        => 'sometimes|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type'        => 'sometimes|string|in:text,file',
            'status'      => 'nullable|string|in:active,inactive',

            'content' => [
                'nullable', // pas obligatoire en update
                function ($attribute, $value, $fail) {
                    $type = request()->input('type');

                    if ($type === 'text' && !is_null($value)) {
                        if (!is_string($value) || strlen($value) > 2000) {
                            $fail("Le champ $attribute doit être un texte valide (max 2000 caractères).");
                        }
                    }

                    if ($type === 'file' && request()->hasFile($attribute)) {
                        $file = request()->file($attribute);
                        if (!$file->isValid() || !in_array($file->extension(), ['jpg', 'jpeg', 'png', 'gif', 'svg'])) {
                            $fail("Le champ $attribute doit être une image valide (jpg, jpeg, png, gif, svg).");
                        }
                    }
                }
            ],
        ];
    }
}
