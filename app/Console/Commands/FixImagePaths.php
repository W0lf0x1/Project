<?php

namespace App\Console\Commands;

use App\Models\Item;
use Illuminate\Console\Command;

class FixImagePaths extends Command
{
    protected $signature = 'fix:image-paths';
    protected $description = 'Исправляет пути к изображениям в базе данных';

    public function handle()
    {
        $items = Item::all();
        
        foreach ($items as $item) {
            $images = is_array($item->images) ? $item->images : json_decode($item->images, true);
            
            if ($images && is_array($images)) {
                $fixedImages = [];
                foreach ($images as $image) {
                    // Удаляем /storage/ из начала пути, если он есть
                    $fixedImage = preg_replace('/^\/storage\//', '', $image);
                    $fixedImages[] = $fixedImage;
                }
                
                $item->images = $fixedImages;
                $item->save();
                
                $this->info("Исправлены пути для товара ID: {$item->id}");
            }
        }
        
        $this->info('Все пути к изображениям исправлены!');
    }
} 