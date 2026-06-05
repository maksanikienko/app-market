<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasTranslations, SoftDeletes;

    public array $translatable = ['name', 'description', 'short_description'];

    protected $fillable = [
        'name', 'slug', 'code', 'price', 'old_price', 'category_id', 'brand_id',
        'description', 'short_description', 'is_active', 'is_new', 'is_hit', 'is_sale',
        'outer_material_id', 'lining_material_id', 'filling_id',
        'length', 'hood', 'detachable_hood', 'waterproof', 'season',
    ];

    protected $appends = ['media_items'];

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('product_images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)->height(300)->sharpen(10)->nonQueued();

        $this->addMediaConversion('large')
            ->width(1200)->height(1200)->nonQueued();
    }

    public function getMediaItemsAttribute(): array
    {
        return $this->getMedia('product_images')
            ->map(fn($m) => [
                'id'           => $m->id,
                'thumb_url'    => $m->getUrl('thumb'),
                'original_url' => $m->getUrl(),
                'order'        => $m->order_column,
            ])->values()->toArray();
    }
    public function brand(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function variants(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function outerMaterial(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductClassifier::class, 'outer_material_id');
    }

    public function liningMaterial(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductClassifier::class, 'lining_material_id');
    }

    public function filling(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductClassifier::class, 'filling_id');
    }

    public function toArray(): array
    {
        $arr = parent::toArray();
        foreach ($this->translatable as $field) {
            $arr[$field] = $this->getTranslations($field);
        }
        return $arr;
    }

//    public function getPriceForCount(): float|int
//    {
//        if (!is_null($this->pivot)) {
//            return $this->pivot->count * $this->price;
//        }
//        return $this->price;
//    }
}
