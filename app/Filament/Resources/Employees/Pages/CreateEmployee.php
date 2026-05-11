<?php

namespace App\Filament\Resources\Employees\Pages;

use App\Filament\Resources\Employees\EmployeeResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;

    /**
     * 🔥 Inject company_id automatically
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['company_id'] = Auth::user()->company_id;

        return $data;
    }

    /**
     * 🔥 Create employee safely + assign role
     */
    protected function handleRecordCreation(array $data): User
    {
        // 👤 Create user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'company_id' => $data['company_id'],
        ]);

        // 🎭 Safe role assignment
        if (!empty($data['role'])) {
            $role = Role::where('name', $data['role'])->first();

            if ($role) {
                $user->syncRoles([$role->name]);
            }
        }

        return $user;
    }
}
