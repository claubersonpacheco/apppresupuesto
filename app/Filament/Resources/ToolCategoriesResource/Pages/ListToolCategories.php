<?php

namespace App\Filament\Resources\ToolCategoriesResource\Pages;

use App\Filament\Resources\ToolCategoriesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListToolCategories extends ListRecords
{
    protected static string $resource = ToolCategoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label("New Category Tool")
            ->translateLabel(),
        ];
    }
}
