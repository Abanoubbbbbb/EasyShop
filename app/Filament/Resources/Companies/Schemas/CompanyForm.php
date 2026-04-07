<?php

namespace App\Filament\Resources\Companies\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;

use Filament\Schemas\Schema;

class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')
                    ->label('اسم الشركة')
                    ->required()   // 🔥 ده مهم
                    ->maxLength(255)
                    ->unique(),

                FileUpload::make('logo')
                    ->label('شعار الشركة')
                    ->directory('logos') // المكان اللي هيتخزن فيه الصورة
                    ->image()
                    ->nullable(),  // اختياري بس مفيد
            ]);
    }
}
