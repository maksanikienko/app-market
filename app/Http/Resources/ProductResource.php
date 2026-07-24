<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'name'              => $this->getTranslations('name'),
            'slug'              => $this->slug,
            'code'              => $this->code,
            'price'             => $this->price,
            'old_price'         => $this->old_price,
            'description'       => $this->getTranslations('description'),
            'short_description' => $this->getTranslations('short_description'),
            'is_active'         => $this->is_active,
            'is_new'            => $this->is_new,
            'is_hit'            => $this->is_hit,
            'is_sale'           => $this->is_sale,
            'length'            => $this->length,
            'hood'              => $this->hood,
            'detachable_hood'   => $this->detachable_hood,
            'waterproof'        => $this->waterproof,
            'season'            => $this->season,
            'media_items'       => $this->media_items,
            'category'          => $this->whenLoaded('category'),
            'brand'             => $this->whenLoaded('brand'),
            'outer_material'    => $this->whenLoaded('outerMaterial'),
            'lining_material'   => $this->whenLoaded('liningMaterial'),
            'filling'           => $this->whenLoaded('filling'),
            'variants'          => $this->whenLoaded('variants'),
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}
