<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([

                // 👤 Name
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                // 📧 Email
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                // 🔑 Password
                TextInput::make('password')
                    ->password()
                    ->required(fn($context) => $context === 'create')
                    ->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null)
                    ->dehydrated(fn($state) => filled($state)),

                // 🎭 Role (controlled by Policy)
                Select::make('role')
                    ->label('Role')
                    ->options(function () {
                        // ❗ هنا ما بقاش فيه Auth logic
                        // كل حاجة هتيجي من Policy layer أو controller

                        return Role::pluck('name', 'name');
                    })
                    ->required()
                    ->afterStateHydrated(function ($component, $record) {
                        if ($record) {
                            $component->state($record->getRoleNames()->first());
                        }
                    })
                    ->dehydrateStateUsing(fn($state) => $state),
            ]);
    }
}
