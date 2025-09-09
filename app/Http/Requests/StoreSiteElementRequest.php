<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSiteElementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'site_element_categorie_id' => 'required|exists:site_element_categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type'        => 'required|string|in:header,footer,sidebar,section,widget',
            'status'      => 'nullable|string|in:active,inactive',

            'content' => [
                'required',
                function ($attribute, $value, $fail) {
                    // Upload d'image
                    if (request()->hasFile($attribute)) {
                        $file = request()->file($attribute);
                        if (!$file->isValid() || !in_array($file->extension(), ['jpg', 'jpeg', 'png', 'gif', 'svg'])) {
                            $fail("Le champ $attribute doit être une image valide (jpg, jpeg, png, gif, svg).");
                        }
                        return;
                    }

                    // URL ou texte
                    if (filter_var($value, FILTER_VALIDATE_URL) || is_string($value) || is_numeric($value)) {
                        return;
                    }

                    $fail("Le champ $attribute doit être du texte, un numéro, une URL ou une image valide.");
                }
            ],
        ];
    }
}
