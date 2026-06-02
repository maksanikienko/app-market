<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'slug'       => 'down-jackets',
                'sort_order' => 1,
                'name'        => ['ru' => 'Пуховики',           'ro' => 'Geci matlasate',       'en' => 'Down Jackets'],
                'description' => ['ru' => 'Тёплые пуховые куртки и пальто', 'ro' => 'Geci și paltoane călduroase din puf', 'en' => 'Warm down coats and jackets'],
            ],
            [
                'slug'       => 'jackets',
                'sort_order' => 2,
                'name'        => ['ru' => 'Куртки',             'ro' => 'Jachete',              'en' => 'Jackets'],
                'description' => ['ru' => 'Демисезонные и зимние куртки', 'ro' => 'Jachete de primăvară, toamnă și iarnă', 'en' => 'Spring, autumn and winter jackets'],
            ],
            [
                'slug'       => 'fur-coats',
                'sort_order' => 3,
                'name'        => ['ru' => 'Шубы и дублёнки',    'ro' => 'Haine de blană',       'en' => 'Fur Coats'],
                'description' => ['ru' => 'Натуральные и искусственные шубы, дублёнки', 'ro' => 'Haine de blană naturală și ecologică', 'en' => 'Natural and faux fur coats'],
            ],
            [
                'slug'       => 'coats',
                'sort_order' => 4,
                'name'        => ['ru' => 'Пальто',             'ro' => 'Paltoane',             'en' => 'Coats'],
                'description' => ['ru' => 'Классические и современные пальто', 'ro' => 'Paltoane clasice și moderne', 'en' => 'Classic and modern coats'],
            ],
            [
                'slug'       => 'parkas',
                'sort_order' => 5,
                'name'        => ['ru' => 'Парки',              'ro' => 'Parka',                'en' => 'Parkas'],
                'description' => ['ru' => 'Удлинённые куртки-парки', 'ro' => 'Jachete tip parka alungite', 'en' => 'Long-style parka jackets'],
            ],
            [
                'slug'       => 'vests',
                'sort_order' => 6,
                'name'        => ['ru' => 'Жилеты',             'ro' => 'Veste',                'en' => 'Vests'],
                'description' => ['ru' => 'Тёплые и утеплённые жилеты', 'ro' => 'Veste călduroase și căptușite', 'en' => 'Warm and padded vests'],
            ],
        ];

        foreach ($categories as $data) {
            Category::create($data);
        }
    }
}