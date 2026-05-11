<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    /*
    |---------------------------------
    | PROTECT COMPANY OWNERSHIP
    |---------------------------------
    */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $userCompanyId = Auth::user()->company_id;

        // 🔥 لو في تعديلات على company_id لازم تكون متوافقة مع ال user
        if (isset($data['company_id']) && $data['company_id'] !== $userCompanyId) {
            abort(403, 'You cannot change the company ID.');
        }

        // 🔐 حماية: منع تعديل أو نقل الكاتيجوري لشركة تانية
        $data['company_id'] = $userCompanyId;

        return $data;
    }

    /*
    |---------------------------------
    | HEADER ACTIONS (شيل حذف إذا مش مناسب)
    |---------------------------------
    */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
