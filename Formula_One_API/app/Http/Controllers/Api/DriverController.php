<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class DriverController extends Controller
{
    public function index(): JsonResponse
    {
        $drivers = Driver::with('team')->orderBy('team_id')->get();

        return response()->json($drivers);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'team_id' => ['nullable', 'integer', 'exists:teams,id'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'number' => ['required', 'integer', 'between:1,99', 'unique:drivers,number'],
            'nationality' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
        ]);

        $driver = Driver::create($validated)->load('team');

        Log::info('Driver created', ['driver_id' => $driver->id]);

        return response()->json($driver, 201);
    }

    public function show(Driver $driver): JsonResponse
    {
        $driver->load('team');

        return response()->json($driver);
    }

    public function update(Request $request, Driver $driver): JsonResponse
    {
        $validated = $request->validate([
            'team_id' => ['nullable', 'integer', 'exists:teams,id'],
            'first_name' => ['sometimes', 'required', 'string', 'max:255'],
            'last_name' => ['sometimes', 'required', 'string', 'max:255'],
            'number' => [
                'sometimes',
                'required',
                'integer',
                'between:1,99',
                Rule::unique('drivers', 'number')->ignore($driver->id),
            ],
            'nationality' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
        ]);

        $driver->update($validated);

        Log::info('Driver updated', ['driver_id' => $driver->id]);

        return response()->json($driver->load('team'));
    }

    public function destroy(Driver $driver): JsonResponse
    {
        $driver->delete();

        Log::info('Driver deleted', ['driver_id' => $driver->id]);

        return response()->json(status: 204);
    }
}