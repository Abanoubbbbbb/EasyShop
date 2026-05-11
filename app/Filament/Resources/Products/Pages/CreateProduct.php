<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Filament\Facades\Filament;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    // 🟢 ربط المنتج بالشركة تلقائيًا (SaaS Multi-Tenant)
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Filament::auth()->user();

        if (! $user?->company_id) {
            abort(403, 'No company assigned to user');
        }

        $data['company_id'] = $user->company_id;

        return $data;
    }
}
