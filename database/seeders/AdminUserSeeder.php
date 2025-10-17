<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Admin kullanıcısı oluştur
        $admin = User::create([
            'name' => 'Kosai',
            'email' => 'rabeeclane2@gmail.com',
            'phone' => '+963123456789',
            'password' => Hash::make('password'),
        ]);

        // Admin rolünü ata
        $adminRole = Role::where('name', 'admin')->first();
        $admin->assignRole($adminRole);

        // Test restoranı oluştur
        $restaurant = \App\Models\Restaurant::create([
            'name' => 'مطعم النخبة',
            'opening_time' => '09:00',
            'closing_time' => '23:00',
            'address' => 'حماة، سوريا',
            'phone' => '+963987654321',
            'is_active' => true,
        ]);

        // Kategoriler oluştur
        $categories = [
            [
                'name' => 'المقبلات',
                'description' => 'أشهى المقبلات السورية',
                'sort_order' => 1,
            ],
            [
                'name' => 'الوجبات الرئيسية',
                'description' => 'أطباق رئيسية لذيذة',
                'sort_order' => 2,
            ],
            [
                'name' => 'المشروبات',
                'description' => 'مشروبات باردة وساخنة',
                'sort_order' => 3,
            ]
        ];

        foreach ($categories as $categoryData) {
            $category = $restaurant->categories()->create($categoryData);

            // Her kategori için ürünler oluştur
            if ($category->name == 'المقبلات') {
                $products = [
                    ['name' => 'حمص', 'price' => 15.00, 'preparation_time' => 10],
                    ['name' => 'متبل', 'price' => 12.00, 'preparation_time' => 8],
                    ['name' => 'فتوش', 'price' => 18.00, 'preparation_time' => 12],
                ];
            } elseif ($category->name == 'الوجبات الرئيسية') {
                $products = [
                    ['name' => 'كباب حلب', 'price' => 45.00, 'preparation_time' => 20],
                    ['name' => 'مسخن', 'price' => 35.00, 'preparation_time' => 15],
                    ['name' => 'مندي لحم', 'price' => 50.00, 'preparation_time' => 25],
                ];
            } else {
                $products = [
                    ['name' => 'عصير برتقال', 'price' => 8.00, 'preparation_time' => 3],
                    ['name' => 'شاي', 'price' => 5.00, 'preparation_time' => 5],
                    ['name' => 'قهوة عربية', 'price' => 10.00, 'preparation_time' => 7],
                ];
            }

            foreach ($products as $productData) {
                $category->products()->create($productData);
            }
        }
    }
}
