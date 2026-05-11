<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Filament\Facades\Filament;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // 🟢 Name
                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set) {
                        // تعيين الـ slug تلقائيًا بناءً على الاسم
                        $set('slug', Str::slug($state));
                    }),

                // 🟢 Slug (STABLE SaaS unique)
                TextInput::make('slug')
                    ->required()
                    ->disabled()
                    ->dehydrated()
                    ->unique(
                        table: 'categories',
                        column: 'slug',
                        ignoreRecord: true,
                        modifyRuleUsing: function (Rule $rule) {
                            // التحقق من الـ company_id قبل تطبيق الـ rule
                            $user = Filament::auth()->user();

                            // 🔥 إذا لم يكن المستخدم مرتبطًا بشركة، نوقف التنفيذ
                            if (! $user?->company_id) {
                                abort(403, 'User not assigned to any company.');
                            }

                            // إضافة شرط الشركة في قاعدة البيانات لضمان فريدة الـ slug لكل شركة
                            return $rule->where('company_id', $user->company_id);
                        }
                    ),

            ]);
    }
}
