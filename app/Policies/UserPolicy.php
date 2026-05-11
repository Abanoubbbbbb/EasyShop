<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * عرض كل المستخدمين داخل نفس الشركة
     */
    public function viewAny(?User $authUser): bool
    {
        if (! $authUser) {
            return false;
        }

        return $authUser->hasAnyRole(['owner', 'admin']);
    }

    /**
     * عرض مستخدم واحد
     */
    public function view(?User $authUser, User $user): bool
    {
        return true; // مسموح للجميع يشوفوا بعض
    }


    /**
     * إنشاء مستخدم (موظف)
     */
    public function create(?User $authUser): bool
    {
        if (! $authUser) {
            return false;
        }

        return $authUser->hasAnyRole(['owner', 'admin']);
    }

    /**
     * تعديل مستخدم
     */
    public function update(?User $authUser, User $user): bool
    {
        if (! $authUser) {
            return false;
        }

        return $authUser->company_id === $user->company_id
            && $authUser->hasAnyRole(['owner', 'admin']);
    }

    /**
     * حذف مستخدم (مسموح للـ owner فقط)
     */
    public function delete(?User $authUser, User $user): bool
    {
        if (! $authUser) {
            return false;
        }

        return $authUser->hasRole('owner')
            && $authUser->company_id === $user->company_id;
    }

    /**
     * منع حذف نفسه
     */
    public function deleteSelf(?User $authUser, User $user): bool
    {
        return $authUser && $authUser->id !== $user->id;
    }
}
