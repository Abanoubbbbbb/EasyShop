<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Tables\Table;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;

use Filament\Tables\Filters\SelectFilter;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table

            ->columns([

                // رقم الطلب
                TextColumn::make('id')
                    ->label('#')
                    ->sortable()
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),

                // العميل
                TextColumn::make('customer_name')
                    ->label('العميل')
                    ->searchable()
                    ->sortable()
                    ->description(fn($record) => $record->phone),

                // الحالة (أفضل UX)
                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'pending' => 'قيد الانتظار',
                        'processing' => 'جاري التجهيز',
                        'completed' => 'تم التسليم',
                        'canceled' => 'ملغي',
                        default => $state,
                    })
                    ->color(fn(string $state) => match ($state) {
                        'pending' => 'warning',
                        'processing' => 'info',
                        'completed' => 'success',
                        'canceled' => 'danger',
                        default => 'gray',
                    }),

                // الإجمالي
                TextColumn::make('total_price')
                    ->label('الإجمالي')
                    ->money('EGP')
                    ->sortable()
                    ->badge()
                    ->color('success'),

                // التاريخ
                TextColumn::make('created_at')
                    ->label('تاريخ الطلب')
                    ->since()
                    ->sortable(),

            ])

            ->filters([

                SelectFilter::make('status')
                    ->label('فلترة بالحالة')
                    ->options([
                        'pending' => 'قيد الانتظار',
                        'processing' => 'جاري التجهيز',
                        'completed' => 'تم التسليم',
                        'canceled' => 'ملغي',
                    ]),

            ])

            ->actions([

                ViewAction::make()
                    ->label('')
                    ->tooltip('عرض'),

                EditAction::make()
                    ->label('')
                    ->tooltip('تعديل'),

                Action::make('whatsapp')
                    ->label('')
                    ->tooltip('واتساب')
                    ->icon('heroicon-m-chat-bubble-left-right')
                    ->color('success')

                    ->visible(fn($record) => filled($record->phone))

                    ->url(
                        fn($record) =>
                        'https://wa.me/2' . preg_replace('/[^0-9]/', '', $record->phone),
                        shouldOpenInNewTab: true
                    ),

            ])

            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])

            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100])

            ->emptyStateHeading('لا توجد طلبات')
            ->emptyStateDescription('ستظهر الطلبات هنا عند إضافة طلبات جديدة')
            ->emptyStateIcon('heroicon-o-shopping-bag');
    }
}
