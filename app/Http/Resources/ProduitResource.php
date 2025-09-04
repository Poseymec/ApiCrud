<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProduitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'categorie_id' => $this->categorie_id,
            'description1' => $this->description1,
            'description2' => $this->description2,
            'prix' => $this->prix,
            'status' => $this->status,
            'images' => $this->images->map(function ($image) {
                return [
                    'id' => $image->id,
                    'image_path' => $image->image_path,
                    'is_cover' => $image->is_cover,
                ];
            }),
            'cover_image' => optional($this->cover)->image_path,
            //categorie du produit
            'categorie' => new CategorieResource($this->whenLoaded('categorie')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
