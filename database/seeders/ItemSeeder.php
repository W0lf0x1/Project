<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создаем тестовых пользователей
        $users = [
            User::create([
                'name' => 'Александр Иванов',
                'email' => 'alex@example.com',
                'password' => bcrypt('password'),
            ]),
            User::create([
                'name' => 'Мария Петрова',
                'email' => 'maria@example.com',
                'password' => bcrypt('password'),
            ]),
            User::create([
                'name' => 'Дмитрий Сидоров',
                'email' => 'dmitry@example.com',
                'password' => bcrypt('password'),
            ]),
            User::create([
                'name' => 'Елена Смирнова',
                'email' => 'elena@example.com',
                'password' => bcrypt('password'),
            ]),
        ];

        // Получаем категории
        $electronics = Category::where('name', 'Электроника')->first();
        $smartphones = Category::where('name', 'Смартфоны')->first();
        $laptops = Category::where('name', 'Ноутбуки')->first();
        $clothing = Category::where('name', 'Одежда')->first();
        $mensClothing = Category::where('name', 'Мужская одежда')->first();
        $womensClothing = Category::where('name', 'Женская одежда')->first();
        $home = Category::where('name', 'Дом и сад')->first();
        $furniture = Category::where('name', 'Мебель')->first();
        $sport = Category::where('name', 'Спорт и отдых')->first();
        $books = Category::where('name', 'Книги и образование')->first();
        $misc = Category::where('name', 'Разное')->first();

        // Массив предметов для обмена
        $items = [
            // Электроника
            [
                'title' => 'iPhone 12 Pro',
                'description' => 'Отличный iPhone в идеальном состоянии. Полный комплект, все работает как новенький.',
                'condition' => 'Отличное',
                'category_id' => $smartphones->id,
                'user_id' => $users[0]->id,
                'images' => ['items/iphone12.jpg'],
            ],
            [
                'title' => 'MacBook Pro 2020',
                'description' => 'Мощный ноутбук для работы и учебы. 16GB RAM, 512GB SSD.',
                'condition' => 'Хорошее',
                'category_id' => $laptops->id,
                'user_id' => $users[1]->id,
                'images' => ['items/macbook.jpg'],
            ],
            [
                'title' => 'Samsung Galaxy S21',
                'description' => 'Современный смартфон с отличной камерой. В комплекте чехол и защитное стекло.',
                'condition' => 'Отличное',
                'category_id' => $smartphones->id,
                'user_id' => $users[2]->id,
                'images' => ['items/samsung.jpg'],
            ],

            // Одежда
            [
                'title' => 'Кожаная куртка',
                'description' => 'Стильная кожаная куртка, размер 48-50. Носили всего несколько раз.',
                'condition' => 'Отличное',
                'category_id' => $mensClothing->id,
                'user_id' => $users[0]->id,
                'images' => ['items/jacket.jpg'],
            ],
            [
                'title' => 'Платье вечернее',
                'description' => 'Элегантное вечернее платье, размер 42-44. Идеально подойдет для особых случаев.',
                'condition' => 'Новое',
                'category_id' => $womensClothing->id,
                'user_id' => $users[1]->id,
                'images' => ['items/dress.jpg'],
            ],

            // Дом и сад
            [
                'title' => 'Диван угловой',
                'description' => 'Удобный угловой диван, хорошее состояние. Подойдет для большой комнаты.',
                'condition' => 'Хорошее',
                'category_id' => $furniture->id,
                'user_id' => $users[2]->id,
                'images' => ['items/sofa.jpg'],
            ],
            [
                'title' => 'Набор садовых инструментов',
                'description' => 'Полный набор инструментов для сада. Все в отличном состоянии.',
                'condition' => 'Отличное',
                'category_id' => $home->id,
                'user_id' => $users[3]->id,
                'images' => ['items/garden.jpg'],
            ],

            // Спорт и отдых
            [
                'title' => 'Велосипед горный',
                'description' => 'Надежный горный велосипед, подойдет для активного отдыха.',
                'condition' => 'Хорошее',
                'category_id' => $sport->id,
                'user_id' => $users[0]->id,
                'images' => ['items/bike.jpg'],
            ],
            [
                'title' => 'Теннисная ракетка',
                'description' => 'Профессиональная теннисная ракетка, используется мало.',
                'condition' => 'Отличное',
                'category_id' => $sport->id,
                'user_id' => $users[1]->id,
                'images' => ['items/tennis.jpg'],
            ],

            // Книги и образование
            [
                'title' => 'Коллекция книг по программированию',
                'description' => 'Набор современных книг по программированию на русском и английском языках.',
                'condition' => 'Хорошее',
                'category_id' => $books->id,
                'user_id' => $users[2]->id,
                'images' => ['items/books.jpg'],
            ],
            [
                'title' => 'Глобус интерактивный',
                'description' => 'Современный интерактивный глобус с подсветкой и описанием стран.',
                'condition' => 'Отличное',
                'category_id' => $books->id,
                'user_id' => $users[3]->id,
                'images' => ['items/globe.jpg'],
            ],

            // Разное
            [
                'title' => 'Набор для рисования',
                'description' => 'Профессиональный набор для рисования, включает краски, кисти и холсты.',
                'condition' => 'Новое',
                'category_id' => $misc->id,
                'user_id' => $users[0]->id,
                'images' => ['items/art.jpg'],
            ],
            [
                'title' => 'Музыкальный инструмент',
                'description' => 'Акустическая гитара, хорошее состояние, подходит для обучения.',
                'condition' => 'Хорошее',
                'category_id' => $misc->id,
                'user_id' => $users[1]->id,
                'images' => ['items/guitar.jpg'],
            ],
        ];

        // Создаем предметы
        foreach ($items as $itemData) {
            $images = [];
            foreach ($itemData['images'] as $image) {
                // Копируем изображение из public/images в storage/items
                if (file_exists(public_path('images/' . $image))) {
                    $newPath = 'items/' . basename($image);
                    Storage::put('public/' . $newPath, file_get_contents(public_path('images/' . $image)));
                    $images[] = '/storage/' . $newPath;
                }
            }
            $itemData['images'] = json_encode($images);
            Item::create($itemData);
        }
    }
} 