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
            'type'        => 'required|string|in:text,file',
            'status'      => 'nullable|string|in:active,inactive',

            // Si type = text => content est du texte
            // Si type = file => content est un fichier
            'content' => [
                'required',
                function ($attribute, $value, $fail) {
                    $type = request()->input('type');

                    if ($type === 'text') {
                        if (!is_string($value) || strlen($value) > 2000) {
                            $fail("Le champ $attribute doit être un texte valide (max 2000 caractères).");
                        }
                    }

                    if ($type === 'file') {
                        if (!request()->hasFile($attribute)) {
                            $fail("Vous devez uploader un fichier pour $attribute.");
                            return;
                        }

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
