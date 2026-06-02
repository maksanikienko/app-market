<?php

namespace Database\Seeders;

use App\Models\ProductClassifier;
use Illuminate\Database\Seeder;

class ProductClassifierSeeder extends Seeder
{
    public function run(): void
    {
        $classifiers = [

            // === OUTER MATERIAL ===
            ['type' => 'outer_material', 'key' => 'polyester',   'name' => ['ru' => 'Полиэстер',          'ro' => 'Poliester',          'en' => 'Polyester']],
            ['type' => 'outer_material', 'key' => 'nylon',       'name' => ['ru' => 'Нейлон',             'ro' => 'Nailon',             'en' => 'Nylon']],
            ['type' => 'outer_material', 'key' => 'membrane',    'name' => ['ru' => 'Мембрана',           'ro' => 'Membrană',           'en' => 'Membrane']],
            ['type' => 'outer_material', 'key' => 'wool',        'name' => ['ru' => 'Шерсть',             'ro' => 'Lână',               'en' => 'Wool']],
            ['type' => 'outer_material', 'key' => 'cashmere',    'name' => ['ru' => 'Кашемир',            'ro' => 'Cașmir',             'en' => 'Cashmere']],
            ['type' => 'outer_material', 'key' => 'leather',     'name' => ['ru' => 'Натуральная кожа',   'ro' => 'Piele naturală',     'en' => 'Genuine Leather']],
            ['type' => 'outer_material', 'key' => 'eco_leather', 'name' => ['ru' => 'Экокожа',            'ro' => 'Piele ecologică',    'en' => 'Eco Leather']],
            ['type' => 'outer_material', 'key' => 'suede',       'name' => ['ru' => 'Замша',              'ro' => 'Piele întoarsă',     'en' => 'Suede']],

            // === LINING MATERIAL ===
            ['type' => 'lining_material', 'key' => 'polyester_lining', 'name' => ['ru' => 'Подкладка полиэстер', 'ro' => 'Căptușeală poliester', 'en' => 'Polyester Lining']],
            ['type' => 'lining_material', 'key' => 'satin',            'name' => ['ru' => 'Атлас',               'ro' => 'Satin',                'en' => 'Satin']],
            ['type' => 'lining_material', 'key' => 'viscose',          'name' => ['ru' => 'Вискоза',             'ro' => 'Vâscoză',              'en' => 'Viscose']],
            ['type' => 'lining_material', 'key' => 'fleece',           'name' => ['ru' => 'Флис',                'ro' => 'Fleece',               'en' => 'Fleece']],
            ['type' => 'lining_material', 'key' => 'cotton',           'name' => ['ru' => 'Хлопок',              'ro' => 'Bumbac',               'en' => 'Cotton']],

            // === FILLING ===
            ['type' => 'filling', 'key' => 'goose_down',   'name' => ['ru' => 'Гусиный пух',    'ro' => 'Puf de gâscă',      'en' => 'Goose Down']],
            ['type' => 'filling', 'key' => 'duck_down',    'name' => ['ru' => 'Утиный пух',     'ro' => 'Puf de rață',       'en' => 'Duck Down']],
            ['type' => 'filling', 'key' => 'hollofiber',   'name' => ['ru' => 'Холлофайбер',    'ro' => 'Hollofiber',        'en' => 'Hollofiber']],
            ['type' => 'filling', 'key' => 'sintepon',     'name' => ['ru' => 'Синтепон',       'ro' => 'Sintepon',          'en' => 'Sintepon']],
            ['type' => 'filling', 'key' => 'thinsulate',   'name' => ['ru' => 'Тинсулейт',      'ro' => 'Thinsulate',        'en' => 'Thinsulate']],
            ['type' => 'filling', 'key' => 'wool_filling', 'name' => ['ru' => 'Овечья шерсть',  'ro' => 'Lână de oaie',      'en' => 'Sheep Wool']],

            // === SEASON ===
            ['type' => 'season', 'key' => 'winter',        'name' => ['ru' => 'Зима',           'ro' => 'Iarnă',             'en' => 'Winter']],
            ['type' => 'season', 'key' => 'demi_season',   'name' => ['ru' => 'Демисезон',      'ro' => 'Demi-sezon',        'en' => 'Demi-Season']],
            ['type' => 'season', 'key' => 'autumn_spring', 'name' => ['ru' => 'Осень-весна',    'ro' => 'Toamnă-primăvară',  'en' => 'Autumn-Spring']],

            // === LENGTH ===
            ['type' => 'length', 'key' => 'short',  'name' => ['ru' => 'Короткая',  'ro' => 'Scurtă',  'en' => 'Short']],
            ['type' => 'length', 'key' => 'medium', 'name' => ['ru' => 'Средняя',   'ro' => 'Medie',   'en' => 'Medium']],
            ['type' => 'length', 'key' => 'long',   'name' => ['ru' => 'Длинная',   'ro' => 'Lungă',   'en' => 'Long']],
        ];

        foreach ($classifiers as $data) {
            ProductClassifier::create($data);
        }
    }
}