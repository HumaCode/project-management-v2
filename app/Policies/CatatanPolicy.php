<?php

namespace App\Policies;

use App\Models\Catatan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CatatanPolicy
{
    /**
     * Determine whether the user can bypass all checks.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('dev') || $user->hasRole('admin')) {
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
    public function view(User $user, Catatan $catatan): bool
    {
        // Anggota hanya bisa melihat catatan jika terdaftar di tim proyek tersebut atau dia pembuatnya
        return ($catatan->project_id && $user->teams()->where('teams.id', $catatan->project->team_id)->exists()) || 
               $catatan->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, ?string $projectId = null): bool
    {
        if ($projectId) {
            $project = \App\Models\Project::find($projectId);
            if ($project) {
                return $user->teams()->where('teams.id', $project->team_id)->exists() || 
                       $project->created_by === $user->id;
            }
        }
        
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Catatan $catatan): bool
    {
        // Anggota tim proyek atau pembuat catatan bisa update
        return ($catatan->project_id && $user->teams()->where('teams.id', $catatan->project->team_id)->exists()) || 
               $catatan->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Catatan $catatan): bool
    {
        // Hapus hanya oleh pembuat catatan atau admin (handled by before)
        return $catatan->user_id === $user->id || 
               ($catatan->project_id && $catatan->project->created_by === $user->id);
    }
}
