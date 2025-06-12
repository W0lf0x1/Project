<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;

class UpdateItemImages extends Command
{
    protected $signature = 'items:update-images';
    protected $description = 'Update item images paths in database';

    protected $itemImageMap = [
        'iPhone 12 Pro' => 'iphone12.jpg',
        'MacBook Pro 2020' => 'macbook.jpg',
        'Samsung Galaxy S21' => 'samsung.jpg',
        'Кожаная куртка' => 'jacket.jpg',
        'Платье вечернее' => 'dress.jpg',
        'Диван угловой' => 'sofa.jpg',
        'Набор садовых инструментов' => 'garden.jpg',
        'Велосипед горный' => 'bike.jpg',
        'Теннисная ракетка' => 'tennis.jpg',
        'Коллекция книг по программированию' => 'books.jpg',
        'Глобус интерактивный' => 'globe.jpg',
        'Набор для рисования' => 'art.jpg',
        'Музыкальный инструмент' => 'guitar.jpg'
    ];

    public function handle()
    {
        $items = Item::all();
        $updated = 0;

        foreach ($items as $item) {
            $currentImages = is_array($item->images) ? $item->images : json_decode($item->images, true);
            
            if (!$currentImages || !is_array($currentImages)) {
                $currentImages = [];
            }

            // Если у товара нет изображений, пытаемся найти подходящее
            if (empty($currentImages) && isset($this->itemImageMap[$item->title])) {
                $imagePath = 'items/' . $this->itemImageMap[$item->title];
                if (Storage::disk('public')->exists($imagePath)) {
                    $currentImages = [$imagePath];
                    $item->images = $currentImages;
                    $item->save();
                    $updated++;
                    $this->info("Added image for item #{$item->id} - {$item->title}");
                }
            }
        }

        $this->info("Updated {$updated} items");
        return 0;
    }
} 