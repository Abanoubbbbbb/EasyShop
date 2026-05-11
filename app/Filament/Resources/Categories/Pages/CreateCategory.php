<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Facades\Filament;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    // 🔥 SaaS safe company injection
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 👇 التأكد من أن المستخدم لديه company_id
        $user = Filament::auth()->user();

        // ✅ إذا كانت الشركة غير موجودة (يعني المستخدم ليس متصل بشركة)
        if (! $user?->company_id) {
            abort(403, 'No company assigned to user');
        }

        // 👇 التأكد من إضافة company_id في البيانات
        $data['company_id'] = $user->company_id;

        return $data;
    }
}
