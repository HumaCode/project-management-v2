<?php

namespace App\Policies;

use App\Models\Diagram;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DiagramPolicy
{
    /**
     * Helper to check if user has access to the diagram's project
     */
    private function hasProjectAccess(User $user, Diagram $diagram): bool
    {
        if ($user->hasRole(['dev', 'admin'])) {
            return true;
        }

        // Cek apakah user adalah bagian dari project
        return $diagram->project->pics()->where('users.id', $user->id)->exists();
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Dibatasi di level query controller
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Diagram $diagram): bool
    {
        return $this->hasProjectAccess($user, $diagram);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Validasi project dilakukan di request
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Diagram $diagram): bool
    {
        return $this->hasProjectAccess($user, $diagram);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Diagram $diagram): bool
    {
        return $this->hasProjectAccess($user, $diagram);
    }

    public function restore(User $user, Diagram $diagram): bool
    {
        return $this->hasProjectAccess($user, $diagram);
    }

    public function forceDelete(User $user, Diagram $diagram): bool
    {
        return $this->hasProjectAccess($user, $diagram);
    }
}
