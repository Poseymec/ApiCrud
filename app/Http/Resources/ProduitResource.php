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
            'price' => $this->price,
            'images' => ProduitImageResource::collection($this->whenLoaded('produit_images')),
            'categorie' => new CategorieResource($this->whenLoaded('categorie')),
            'created_at' => $this->created_at->format('y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('y-m-d H:i:s'),
        ];
    }
}
