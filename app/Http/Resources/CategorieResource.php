<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategorieResource extends JsonResource
{
    /**
     * tranformer les resource en tableau
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'produits'=>ProduitResource::collection($this->whenLoaded('produits')),
            'created_at'=>$this->created_at->format('y-m-d H:i:s'),
            'updated_at'=>$this->updated_at->format('y-m-d H:i:s'),
        ];
    }
}
