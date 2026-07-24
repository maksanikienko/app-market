<?php

namespace App\Models\Concerns;

trait MergesTranslatable
{
    public function toArray(): array
    {
        $arr = parent::toArray();

        foreach ($this->translatable as $field) {
            $arr[$field] = $this->getTranslations($field);
        }

        return $arr;
    }
}
