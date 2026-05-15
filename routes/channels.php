<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('project.{id}', function ($user, $id) {
    $project = \App\Models\Project::with('team.members')->find($id);
    if (!$project) return false;

    return $user->hasRole(['admin', 'dev']) || 
           $project->created_by === $user->id || 
           ($project->team && $project->team->members->contains('id', $user->id));
});
