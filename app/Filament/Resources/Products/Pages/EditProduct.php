<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Facades\Filament;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    /*
    |---------------------------------
    | PROTECT COMPANY OWNERSHIP
    |---------------------------------
    */

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = Filament::auth()->user();

        if (! $user?->company_id) {
            abort(403, 'No company assigned to user');
        }

        // 🔥 منع تغيير الشركة نهائيًا
        $data['company_id'] = $user->company_id;

        return $data;
    }

    /*
    |---------------------------------
    | HEADER ACTIONS
    |---------------------------------
    */

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
