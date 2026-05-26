<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Oficios;
use Illuminate\Auth\Access\HandlesAuthorization;

class OficiosPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Oficios');
    }

    public function view(AuthUser $authUser, Oficios $oficios): bool
    {
        return $authUser->can('View:Oficios');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Oficios');
    }

    public function update(AuthUser $authUser, Oficios $oficios): bool
    {
        return $authUser->can('Update:Oficios');
    }

    public function delete(AuthUser $authUser, Oficios $oficios): bool
    {
        return $authUser->can('Delete:Oficios');
    }

    public function restore(AuthUser $authUser, Oficios $oficios): bool
    {
        return $authUser->can('Restore:Oficios');
    }

    public function forceDelete(AuthUser $authUser, Oficios $oficios): bool
    {
        return $authUser->can('ForceDelete:Oficios');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Oficios');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Oficios');
    }

    public function replicate(AuthUser $authUser, Oficios $oficios): bool
    {
        return $authUser->can('Replicate:Oficios');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Oficios');
    }

}