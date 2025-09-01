<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
   public function viewAny(User $userActing): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $manipulatedUser, User $userActing): bool
    {
        return $manipulatedUser->usr_id === $userActing || $userActing->role == 'ADMIN';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $userActing): bool
    {
        return $userActing->role === 'ADMIN';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $manipulatedUser, User $userActing): bool
    {
        return $manipulatedUser->role != 'ADMIN' && $userActing->role == 'ADMIN';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $manipulatedUser, User $userActing): bool
    {
        return $manipulatedUser->role != 'ADMIN' && $userActing->role == 'ADMIN';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $manipulatedUser, User $userActing): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $manipulatedUser, User $userActing): bool
    {
        return false;
    }

    public function viewAll(User $userActing): bool
    {
        return $userActing->role == 'ADMIN';
    }

    public function viewHomeAdmin(User $userActing): bool
    {
        return $userActing->role == 'ADMIN';
    }
}
