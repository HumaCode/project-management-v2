<?php

namespace App\Repositories\Team;

use App\Interface\Team\TeamRepositoryInterface;
use App\Models\Team;
use Illuminate\Support\Facades\DB;

class TeamRepository implements TeamRepositoryInterface
{
    public function getAll(?string $search, int $rowPerPage)
    {
        $query = Team::with(['creator', 'members.media', 'members.roles']);

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
        return Team::with(['creator', 'members.media', 'members.roles'])->findOrFail($id);
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

        return $team;
    }

    public function delete(string $id)
    {
        $team = Team::findOrFail($id);
        return $team->delete();
    }

    public function countTeams(): int
    {
        return Team::count();
    }

    public function countDistinctMembers(): int
    {
        return DB::table('team_user')->distinct('user_id')->count();
    }

    public function all()
    {
        return Team::orderBy('name')->get();
    }
}
