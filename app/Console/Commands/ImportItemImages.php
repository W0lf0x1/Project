<?php

namespace App\Console\Commands;

use App\Models\Item;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ImportItemImages extends Command
{
    protected $signature = 'items:import-images {source_dir}';
    protected $description = 'Импортирует изображения для товаров из указанной директории';

    public function handle()
    {
        $sourceDir = $this->argument('source_dir');
        
        if (!is_dir($sourceDir)) {
            $this->error("Директория {$sourceDir} не существует!");
            return 1;
        }

        $items = Item::with('user')->get();
        $files = glob($sourceDir . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        
        if (empty($files)) {
            $this->error("В директории {$sourceDir} нет изображений!");
            return 1;
        }

        $this->info("Найдено " . count($files) . " изображений");
        $this->info("Найдено " . count($items) . " товаров");

        // Показываем список товаров
        $this->info("\nСписок товаров:");
        foreach ($items as $index => $item) {
            $this->line("[{$index}] ID: {$item->id} | Название: {$item->title} | Пользователь: {$item->user->name}");
        }

        // Показываем список изображений
        $this->info("\nСписок изображений:");
        foreach ($files as $index => $file) {
            $this->line("[{$index}] " . basename($file));
        }

        // Запрашиваем у пользователя соответствие
        foreach ($files as $fileIndex => $file) {
            $this->info("\nОбработка изображения: " . basename($file));
            
            $itemIndex = $this->ask("Введите номер товара для этого изображения (или 'skip' чтобы пропустить)");
            
            if ($itemIndex === 'skip') {
                continue;
            }
            
            if (!isset($items[$itemIndex])) {
                $this->error("Неверный номер товара!");
                continue;
            }
            
            $item = $items[$itemIndex];
            $filename = basename($file);
            $newPath = 'items/' . uniqid() . '_' . $filename;
            
            // Копируем файл в хранилище
            Storage::disk('public')->put($newPath, file_get_contents($file));
            
            // Обновляем запись в базе данных
            $images = is_array($item->images) ? $item->images : json_decode($item->images, true);
            if (!is_array($images)) {
                $images = [];
            }
            $images[] = $newPath;
            
            $item->update(['images' => $images]);
            
            $this->info("Добавлено изображение для товара #{$item->id}: {$filename}");
        }

        $this->info("\nИмпорт завершен!");
        return 0;
    }
} 