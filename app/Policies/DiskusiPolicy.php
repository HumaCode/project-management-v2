<?php

namespace App\Policies;

use App\Models\Diskusi;
use App\Models\User;

class DiskusiPolicy
{
    /**
     * Policy ini mengatur siapa saja yang boleh melakukan aksi pada fitur Diskusi.
     */

    /**
     * Filter sebelum method lain dijalankan.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole(['dev', 'admin'])) {
            return true;
        }

        return null;
    }

    /**
     * Menentukan apakah user boleh mengirim komentar di sebuah project.
     * User harus terdaftar dalam tim project tersebut atau pembuatnya.
     */
    public function create(User $user, \App\Models\Project $project): bool
    {
        if ($project->created_by === $user->id) {
            return true;
        }

        return $user->teams()->where('teams.id', $project->team_id)->exists();
    }

    /**
     * Menentukan apakah user boleh mengubah komentar.
     * Hanya pemilik komentar yang boleh mengubahnya, dan dibatasi waktu (misal 5 menit).
     */
    public function update(User $user, Diskusi $diskusi): bool
    {
        // Hanya pemilik komentar
        if ($diskusi->user_id !== $user->id) {
            return false;
        }

        // Batas waktu edit 5 menit
        return $diskusi->created_at->diffInMinutes(now()) < 5;
    }

    /**
     * Menentukan apakah user boleh menghapus komentar.
     * Pemilik komentar boleh menghapus kapan saja (Admin/Dev sudah dicover di before).
     */
    public function delete(User $user, Diskusi $diskusi): bool
    {
        return $diskusi->user_id === $user->id;
    }
}
