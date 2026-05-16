<?php

namespace App\Policies;

use App\Models\Laporan;
use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LaporanPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole(['admin', 'dev'])) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Laporan $laporan): bool
    {
        return $user->teams()->where('teams.id', $laporan->project->team_id)->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Project $project): bool
    {
        return $user->teams()->where('teams.id', $project->team_id)->exists();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Laporan $laporan): bool
    {
        return false; // Reports are historical, usually not updated
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Laporan $laporan): bool
    {
        return $user->id === $laporan->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Laporan $laporan): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Laporan $laporan): bool
    {
        return false;
    }
}
