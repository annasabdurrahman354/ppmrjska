<?php

namespace App\Policies;

use App\Models\User;
use App\Models\DewanGuru;
use Illuminate\Auth\Access\HandlesAuthorization;

class DewanGuruPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_dewan::guru');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DewanGuru  $dewanGuru
     * @return bool
     */
    public function view(User $user, DewanGuru $dewanGuru): bool
    {
        return $user->can('view_dewan::guru');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create_dewan::guru');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DewanGuru  $dewanGuru
     * @return bool
     */
    public function update(User $user, DewanGuru $dewanGuru): bool
    {
        return $user->can('update_dewan::guru');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DewanGuru  $dewanGuru
     * @return bool
     */
    public function delete(User $user, DewanGuru $dewanGuru): bool
    {
        return $user->can('delete_dewan::guru');
    }

    /**
     * Determine whether the user can bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_dewan::guru');
    }

    /**
     * Determine whether the user can permanently delete.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DewanGuru  $dewanGuru
     * @return bool
     */
    public function forceDelete(User $user, DewanGuru $dewanGuru): bool
    {
        return $user->can('force_delete_dewan::guru');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_dewan::guru');
    }

    /**
     * Determine whether the user can restore.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DewanGuru  $dewanGuru
     * @return bool
     */
    public function restore(User $user, DewanGuru $dewanGuru): bool
    {
        return $user->can('restore_dewan::guru');
    }

    /**
     * Determine whether the user can bulk restore.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_dewan::guru');
    }

    /**
     * Determine whether the user can replicate.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DewanGuru  $dewanGuru
     * @return bool
     */
    public function replicate(User $user, DewanGuru $dewanGuru): bool
    {
        return $user->can('replicate_dewan::guru');
    }

    /**
     * Determine whether the user can reorder.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_dewan::guru');
    }

}
