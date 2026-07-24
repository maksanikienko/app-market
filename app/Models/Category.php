<?php

namespace App\Models;

use App\Models\Concerns\MergesTranslatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasFactory, HasTranslations, MergesTranslatable;

    protected $fillable = ['name', 'slug', 'description', 'is_active', 'sort_order'];

    public array $translatable = ['name', 'description'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}