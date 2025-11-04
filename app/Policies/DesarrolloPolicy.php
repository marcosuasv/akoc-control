<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Desarrollo;
use Illuminate\Auth\Access\HandlesAuthorization;

class DesarrolloPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Desarrollo');
    }

    public function view(AuthUser $authUser, Desarrollo $desarrollo): bool
    {
        return $authUser->can('View:Desarrollo');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Desarrollo');
    }

    public function update(AuthUser $authUser, Desarrollo $desarrollo): bool
    {
        return $authUser->can('Update:Desarrollo');
    }

    public function delete(AuthUser $authUser, Desarrollo $desarrollo): bool
    {
        return $authUser->can('Delete:Desarrollo');
    }

    public function restore(AuthUser $authUser, Desarrollo $desarrollo): bool
    {
        return $authUser->can('Restore:Desarrollo');
    }

    public function forceDelete(AuthUser $authUser, Desarrollo $desarrollo): bool
    {
        return $authUser->can('ForceDelete:Desarrollo');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Desarrollo');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Desarrollo');
    }

    public function replicate(AuthUser $authUser, Desarrollo $desarrollo): bool
    {
        return $authUser->can('Replicate:Desarrollo');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Desarrollo');
    }

}