<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            [
                'name'        => 'Reserved',
                'slug'        => Str::slug('Reserved'),
                'logo'        => null,
                'description' => 'Popular Polish brand offering stylish and affordable women\'s outerwear.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Cropp',
                'slug'        => Str::slug('Cropp'),
                'logo'        => null,
                'description' => 'Casual Polish brand with modern jackets and coats for young women.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Sinsay',
                'slug'        => Str::slug('Sinsay'),
                'logo'        => null,
                'description' => 'Trendy and budget-friendly fashion brand from the LPP group.',
                'is_active'   => true,
            ],
            [
                'name'        => 'House',
                'slug'        => Str::slug('House'),
                'logo'        => null,
                'description' => 'Polish brand specializing in casual and streetwear style clothing.',
                'is_active'   => true,
            ],
            [
                'name'        => 'LC Waikiki',
                'slug'        => Str::slug('LC Waikiki'),
                'logo'        => null,
                'description' => 'Turkish brand known for comfortable and warm winter clothing at good prices.',
                'is_active'   => true,
            ],
            [
                'name'        => 'DeFacto',
                'slug'        => Str::slug('DeFacto'),
                'logo'        => null,
                'description' => 'Turkish mass-market brand with a wide selection of women\'s jackets and coats.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Koton',
                'slug'        => Str::slug('Koton'),
                'logo'        => null,
                'description' => 'Turkish fashion brand offering modern and feminine outerwear.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Mango',
                'slug'        => Str::slug('Mango'),
                'logo'        => null,
                'description' => 'Spanish brand with elegant and contemporary women\'s coats and jackets.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Only',
                'slug'        => Str::slug('Only'),
                'logo'        => null,
                'description' => 'Danish brand focused on casual and feminine women\'s clothing.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Vero Moda',
                'slug'        => Str::slug('Vero Moda'),
                'logo'        => null,
                'description' => 'Scandinavian brand with stylish and quality women\'s outerwear.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Pull & Bear',
                'slug'        => Str::slug('Pull & Bear'),
                'logo'        => null,
                'description' => 'Youth-oriented brand with trendy jackets and casual winter wear.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Stradivarius',
                'slug'        => Str::slug('Stradivarius'),
                'logo'        => null,
                'description' => 'Fashionable women\'s brand with elegant and modern coats.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Bershka',
                'slug'        => Str::slug('Bershka'),
                'logo'        => null,
                'description' => 'Trendy Spanish brand popular among young women.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Terranova',
                'slug'        => Str::slug('Terranova'),
                'logo'        => null,
                'description' => 'Italian brand offering comfortable and warm everyday outerwear.',
                'is_active'   => true,
            ],
        ];

        DB::table('brands')->insert($brands);
    }
}