<?php

namespace App\Models;

use App\Models\Concerns\MergesTranslatable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ProductClassifier extends Model
{
    use HasTranslations, MergesTranslatable;

    protected $fillable = ['type', 'key', 'name', 'is_active'];

    public array $translatable = ['name'];
}