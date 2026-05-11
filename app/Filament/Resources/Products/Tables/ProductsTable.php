<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                // 🟢 ID
                TextColumn::make('id')->sortable(),

                // 🟢 Name
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                // 🔥 Cost Price
                TextColumn::make('cost_price')
                    ->label('Cost')
                    ->money('EGP')
                    ->sortable(),

                // 🔥 Sale Price
                TextColumn::make('sale_price')
                    ->label('Sale')
                    ->money('EGP')
                    ->sortable(),

                // 🔥 Profit (مهم جدًا)
                TextColumn::make('profit')
                    ->label('Profit')
                    ->money('EGP')
                    ->getStateUsing(
                        fn($record) => ($record->sale_price - $record->cost_price)
                    )
                    ->color('success')
                    ->sortable(),

                // 🟢 Quantity
                TextColumn::make('quantity')
                    ->label('Stock')
                    ->sortable()
                    ->badge(),

                // 🟢 Image
                ImageColumn::make('image')
                    ->label('Image')
                    ->disk('public')
                    ->height(60)
                    ->width(60)
                    ->square(),

                // 🟢 Company
                TextColumn::make('company.name')
                    ->label('Company')
                    ->sortable(),

                // 🟢 Created
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->searchable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
