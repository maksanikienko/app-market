<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['name', 'slug', 'description', 'is_active', 'sort_order'];

    public array $translatable = ['name', 'description'];

    public function toArray(): array
    {
        $arr = parent::toArray();
        foreach ($this->translatable as $field) {
            $arr[$field] = $this->getTranslations($field);
        }
        return $arr;
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}