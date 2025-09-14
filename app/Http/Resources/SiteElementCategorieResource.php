<?php

namespace App\Http\Resources;

use App\Http\Resources\SiteElementResource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SiteElementCategorieResource extends JsonResource
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
            'name'        => $this->name,
            // Relations éventuelles (exemple avec siteElements)
            'site_elements' => SiteElementResource::collection($this->whenLoaded('siteElements')),
            'created_at'  => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'  => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }


}
