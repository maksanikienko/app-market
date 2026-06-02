<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ProductClassifier extends Model
{
    use HasTranslations;

    protected $fillable = ['type', 'key', 'name', 'is_active'];

    public array $translatable = ['name'];

    public function toArray(): array
    {
        $arr = parent::toArray();
        foreach ($this->translatable as $field) {
            $arr[$field] = $this->getTranslations($field);
        }
        return $arr;
    }
}