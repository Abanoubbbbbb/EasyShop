<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    // 🔐 Force company isolation
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['company_id'] = Auth::user()?->company_id;

        return $data;
    }

    // 🎯 بعد إنشاء المستخدم
    protected function afterCreate(): void
    {
        // 🔥 الطريقة الصح في Filament
        $role = $this->data['role'] ?? null;

        if ($role) {
            $this->record->syncRoles([$role]);
        }

        // 🔔 Notification للتأكيد
        Notification::make()
            ->title('User Created Successfully')
            ->body('Role: ' . ($role ?? 'Not Assigned'))
            ->success()
            ->send();
    }
}
