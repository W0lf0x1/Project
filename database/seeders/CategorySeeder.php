<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Электроника',
                'description' => 'Компьютеры, смартфоны, планшеты и другие электронные устройства',
                'icon' => 'fas fa-laptop',
                'children' => [
                    [
                        'name' => 'Смартфоны',
                        'description' => 'Мобильные телефоны и смартфоны',
                        'icon' => 'fas fa-mobile-alt',
                    ],
                    [
                        'name' => 'Ноутбуки',
                        'description' => 'Портативные компьютеры',
                        'icon' => 'fas fa-laptop',
                    ],
                    [
                        'name' => 'Планшеты',
                        'description' => 'Планшетные компьютеры',
                        'icon' => 'fas fa-tablet-alt',
                    ],
                    [
                        'name' => 'Аксессуары',
                        'description' => 'Чехлы, зарядные устройства и другие аксессуары',
                        'icon' => 'fas fa-headphones',
                    ],
                ],
            ],
            [
                'name' => 'Одежда',
                'description' => 'Мужская, женская и детская одежда',
                'icon' => 'fas fa-tshirt',
                'children' => [
                    [
                        'name' => 'Мужская одежда',
                        'description' => 'Одежда для мужчин',
                        'icon' => 'fas fa-male',
                    ],
                    [
                        'name' => 'Женская одежда',
                        'description' => 'Одежда для женщин',
                        'icon' => 'fas fa-female',
                    ],
                    [
                        'name' => 'Детская одежда',
                        'description' => 'Одежда для детей',
                        'icon' => 'fas fa-baby',
                    ],
                    [
                        'name' => 'Обувь',
                        'description' => 'Обувь для всех',
                        'icon' => 'fas fa-shoe-prints',
                    ],
                ],
            ],
            [
                'name' => 'Дом и сад',
                'description' => 'Предметы для дома и сада',
                'icon' => 'fas fa-home',
                'children' => [
                    [
                        'name' => 'Мебель',
                        'description' => 'Мебель для дома и офиса',
                        'icon' => 'fas fa-couch',
                    ],
                    [
                        'name' => 'Кухня',
                        'description' => 'Посуда и кухонная техника',
                        'icon' => 'fas fa-utensils',
                    ],
                    [
                        'name' => 'Сад',
                        'description' => 'Садовый инвентарь и растения',
                        'icon' => 'fas fa-seedling',
                    ],
                    [
                        'name' => 'Интерьер',
                        'description' => 'Декоративные элементы для интерьера',
                        'icon' => 'fas fa-paint-brush',
                    ],
                ],
            ],
            [
                'name' => 'Спорт и отдых',
                'description' => 'Спортивный инвентарь и товары для отдыха',
                'icon' => 'fas fa-futbol',
                'children' => [
                    [
                        'name' => 'Спортивный инвентарь',
                        'description' => 'Инвентарь для различных видов спорта',
                        'icon' => 'fas fa-basketball-ball',
                    ],
                    [
                        'name' => 'Велосипеды',
                        'description' => 'Велосипеды и аксессуары',
                        'icon' => 'fas fa-bicycle',
                    ],
                    [
                        'name' => 'Туризм',
                        'description' => 'Снаряжение для туризма и походов',
                        'icon' => 'fas fa-hiking',
                    ],
                    [
                        'name' => 'Фитнес',
                        'description' => 'Оборудование для фитнеса',
                        'icon' => 'fas fa-dumbbell',
                    ],
                ],
            ],
            [
                'name' => 'Книги и образование',
                'description' => 'Книги, учебники и образовательные материалы',
                'icon' => 'fas fa-book',
                'children' => [
                    [
                        'name' => 'Художественная литература',
                        'description' => 'Романы, рассказы и другие художественные произведения',
                        'icon' => 'fas fa-book-open',
                    ],
                    [
                        'name' => 'Учебники',
                        'description' => 'Учебники и учебные пособия',
                        'icon' => 'fas fa-graduation-cap',
                    ],
                    [
                        'name' => 'Детские книги',
                        'description' => 'Книги для детей',
                        'icon' => 'fas fa-child',
                    ],
                    [
                        'name' => 'Научная литература',
                        'description' => 'Научные книги и публикации',
                        'icon' => 'fas fa-flask',
                    ],
                ],
            ],
            [
                'name' => 'Разное',
                'description' => 'Различные предметы, не вошедшие в другие категории',
                'icon' => 'fas fa-box',
            ],
        ];

        foreach ($categories as $categoryData) {
            $children = $categoryData['children'] ?? [];
            unset($categoryData['children']);
            
            $categoryData['slug'] = Str::slug($categoryData['name']);
            $category = Category::create($categoryData);
            
            foreach ($children as $childData) {
                $childData['slug'] = Str::slug($childData['name']);
                $childData['parent_id'] = $category->id;
                Category::create($childData);
            }
        }
    }
} 