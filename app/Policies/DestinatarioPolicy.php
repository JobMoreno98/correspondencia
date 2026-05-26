<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Destinatario;
use Illuminate\Auth\Access\HandlesAuthorization;

class DestinatarioPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Destinatario');
    }

    public function view(AuthUser $authUser, Destinatario $destinatario): bool
    {
        return $authUser->can('View:Destinatario');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Destinatario');
    }

    public function update(AuthUser $authUser, Destinatario $destinatario): bool
    {
        return $authUser->can('Update:Destinatario');
    }

    public function delete(AuthUser $authUser, Destinatario $destinatario): bool
    {
        return $authUser->can('Delete:Destinatario');
    }

    public function restore(AuthUser $authUser, Destinatario $destinatario): bool
    {
        return $authUser->can('Restore:Destinatario');
    }

    public function forceDelete(AuthUser $authUser, Destinatario $destinatario): bool
    {
        return $authUser->can('ForceDelete:Destinatario');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Destinatario');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Destinatario');
    }

    public function replicate(AuthUser $authUser, Destinatario $destinatario): bool
    {
        return $authUser->can('Replicate:Destinatario');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Destinatario');
    }

}