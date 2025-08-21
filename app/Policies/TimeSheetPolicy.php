<?php

namespace App\Policies;

use App\Models\TimeSheet;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TimeSheetPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->role === 'USER';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TimeSheet $timeSheet): bool
    {
        return $user->role === 'USER' && $user->usr_id === $timeSheet->tis_usr_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TimeSheet $timeSheet): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TimeSheet $timeSheet): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TimeSheet $timeSheet): bool
    {
        return false;
    }

    public function viewAll(User $user): bool
    {
        return $user->role === 'USER';
    }
}
