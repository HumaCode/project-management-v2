<?php

namespace App\Repositories\Team;

use App\Interface\Team\TeamRepositoryInterface;
use App\Models\Team;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class TeamRepository implements TeamRepositoryInterface
{
    public function getAll(?string $search, int $rowPerPage)
    {
        $query = Team::with([
            'creator:id,name', 
            'members:id,name,username,email,avatar', 
            'members.media', 
            'members.roles:id,name'
        ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($rowPerPage);
    }

    public function findById(string $id)
    {
        return Team::with([
            'creator:id,name', 
            'members:id,name,username,email,avatar', 
            'members.media', 
            'members.roles:id,name'
        ])->findOrFail($id);
    }

    public function create(array $data)
    {
        $team = Team::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'created_by' => auth()->id(),
        ]);

        if (isset($data['members']) && is_array($data['members'])) {
            $syncData = [];
            foreach ($data['members'] as $member) {
                if (isset($member['id'])) {
                    $syncData[$member['id']] = ['role' => $member['role'] ?? null];
                }
            }
            $team->members()->sync($syncData);
        }

        $this->clearCache();

        return $team;
    }

    public function update(string $id, array $data)
    {
        $team = Team::findOrFail($id);
        $team->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        if (isset($data['members']) && is_array($data['members'])) {
            $syncData = [];
            foreach ($data['members'] as $member) {
                if (isset($member['id'])) {
                    $syncData[$member['id']] = ['role' => $member['role'] ?? null];
                }
            }
            $team->members()->sync($syncData);
        }

        $this->clearCache();

        return $team;
    }

    public function delete(string $id)
    {
        $team = Team::findOrFail($id);
        $deleted = $team->delete();
        if ($deleted) {
            $this->clearCache();
        }
        return $deleted;
    }

    public function countTeams(): int
    {
        return Team::count();
    }

    public function countDistinctMembers(): int
    {
        return DB::table('team_user')->distinct()->count('user_id');
    }

    public function clearCache(): void
    {
        Cache::forget('team_count_total');
        Cache::forget('team_member_count_distinct');
        
        // Invalidate cache list project agar perubahan anggota tim langsung memperbarui tampilan project per user
        $version = Cache::get('project_cache_version', 1);
        Cache::forever('project_cache_version', $version + 1);
    }

    public function all()
    {
        return Team::orderBy('name')->get();
    }
}
