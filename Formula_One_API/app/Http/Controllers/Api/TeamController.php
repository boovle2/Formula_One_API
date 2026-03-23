<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeamController extends Controller
{
    public function index(): JsonResponse
    {
        $teams = Team::withCount('drivers')->orderBy('name')->get();

        return response()->json($teams);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:teams,name'],
            'base_country' => ['nullable', 'string', 'max:255'],
            'team_principal' => ['nullable', 'string', 'max:255'],
        ]);

        $team = Team::create($validated);

        return response()->json($team, 201);
    }

    public function show(Team $team): JsonResponse
    {
        $team->load('drivers');

        return response()->json($team);
    }

    public function update(Request $request, Team $team): JsonResponse
    {
        $validated = $request->validate([
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('teams', 'name')->ignore($team->id),
            ],
            'base_country' => ['nullable', 'string', 'max:255'],
            'team_principal' => ['nullable', 'string', 'max:255'],
        ]);

        $team->update($validated);

        return response()->json($team);
    }

    public function destroy(Team $team): JsonResponse
    {
        $team->delete();

        return response()->json(status: 204);
    }
}