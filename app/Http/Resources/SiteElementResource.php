<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class SiteElementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'site_element_categorie_id' => $this->site_element_categorie_id,
            'name'        => $this->name,
            'description' => $this->description,
            'type'        => $this->type,     // ex: text file
            'status'      => $this->status,   // ex: active / inactive
            'content'     => $this->content,  // texte, URL ou chemin fichier
            // Charger la catégorie seulement si elle est disponible
            'categorie'   => new SiteElementCategorieResource($this->whenLoaded('siteElementCategorie')),

            'created_at'  => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'  => $this->updated_at?->format('Y-m-d H:i:s'),

        ];
    }
}

