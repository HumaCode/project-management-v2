<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Policy ini mengatur siapa saja yang boleh mengakses detail project.
     * Secara default, Super Admin (dev/admin) memiliki akses ke semua project.
     * Untuk user lain, mereka harus terdaftar sebagai anggota tim di project tersebut.
     */

    /**
     * Filter sebelum method lain dijalankan.
     * Berguna untuk memberikan akses penuh kepada role tertentu.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole(['dev', 'admin'])) {
            return true;
        }

        return null;
    }

    /**
     * Menentukan apakah user boleh melihat detail project.
     */
    public function view(User $user, Project $project): bool
    {
        // Jika user adalah pembuat project
        if ($project->created_by === $user->id) {
            return true;
        }

        // Jika user terdaftar dalam tim project tersebut
        return $user->teams()->where('teams.id', $project->team_id)->exists();
    }

    /**
     * Menentukan apakah user boleh mengedit project.
     */
    public function update(User $user, Project $project): bool
    {
        // Creator boleh update
        if ($project->created_by === $user->id) {
            return true;
        }

        // Anggota tim boleh update
        return $user->teams()->where('teams.id', $project->team_id)->exists();
    }

    /**
     * Menentukan apakah user boleh menghapus project.
     */
    public function delete(User $user, Project $project): bool
    {
        return $project->created_by === $user->id;
    }
}
