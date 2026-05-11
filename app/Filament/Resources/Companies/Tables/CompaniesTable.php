<?php

namespace App\Filament\Resources\Companies\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;

class CompaniesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                // 🆔 ID
                TextColumn::make('id')
                    ->sortable(),

                // 🏢 Name
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                // 🖼 Logo
                ImageColumn::make('logo')
                    ->rounded(),

                // 📦 Plan
                TextColumn::make('plan')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'free' => 'gray',
                        'monthly' => 'info',
                        'yearly' => 'success',
                        default => 'gray',
                    }),

                // 💰 Price
                TextColumn::make('subscription_price')
                    ->label('Price')
                    ->money('EGP')
                    ->sortable(),

                // 📅 End Date
                TextColumn::make('subscription_ends_at')
                    ->label('Ends At')
                    ->date()
                    ->sortable(),

                // ⏳ Days Left (🔥 مهم)
                TextColumn::make('days_left')
                    ->label('Days Left')
                    ->badge()
                    ->color(fn($state) => match (true) {
                        $state === null => 'gray',
                        $state <= 0 => 'danger',
                        $state <= 3 => 'warning',
                        default => 'success',
                    }),

                // ✅ Active / ❌ Disabled
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                // ⚠️ Expired Status
                IconColumn::make('is_expired')
                    ->label('Expired')
                    ->boolean(),

                // 🕒 Created
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),

            ])

            ->filters([
                //
            ])

            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
