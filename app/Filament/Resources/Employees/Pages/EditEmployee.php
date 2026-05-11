<?php

namespace App\Filament\Resources\Employees\Pages;

use App\Filament\Resources\Employees\EmployeeResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEmployee extends EditRecord
{
    protected static string $resource = EmployeeResource::class;

    /**
     * 🧠 تنظيف الداتا قبل الحفظ
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // لو الباسورد فاضي متغيروش
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        return $data;
    }

    /**
     * 🔥 بعد الحفظ: ربط الـ role
     */
    protected function afterSave(): void
    {
        /** @var User $user */
        $user = $this->record;

        if (!empty($this->data['role'])) {
            $user->syncRoles([$this->data['role']]);
        }
    }

    /**
     * 🗑️ Actions
     */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
