<?php

namespace App\Policies;

use App\Models\Dokumen;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DokumenPolicy
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
    public function view(User $user, Dokumen $dokumen): bool
    {
        // Anggota hanya bisa melihat dokumen jika terdaftar di tim proyek tersebut
        return $user->teams()->where('teams.id', $dokumen->project->team_id)->exists() || 
               $dokumen->user_id === $user->id;
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
        
        return true; // Default true, filter di level query/store
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Dokumen $dokumen): bool
    {
        // Anggota tim proyek atau pengunggah bisa update
        return $user->teams()->where('teams.id', $dokumen->project->team_id)->exists() || 
               $dokumen->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Dokumen $dokumen): bool
    {
        // Biasanya hapus lebih ketat: hanya pengunggah, creator project, atau admin
        return $dokumen->user_id === $user->id || 
               $dokumen->project->created_by === $user->id;
    }
}
