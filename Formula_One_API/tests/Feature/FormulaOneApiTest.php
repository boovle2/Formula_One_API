<?php

use App\Models\Driver;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it can create and list teams', function () {
    $createResponse = $this->postJson('/api/teams', [
        'name' => 'McLaren',
        'base_country' => 'United Kingdom',
        'team_principal' => 'Andrea Stella',
    ]);

    $createResponse
        ->assertCreated()
        ->assertJsonPath('name', 'McLaren');

    $listResponse = $this->getJson('/api/teams');

    $listResponse
        ->assertOk()
        ->assertJsonCount(1)
        ->assertJsonPath('0.name', 'McLaren');
});

test('it can create and update drivers', function () {
    $team = Team::create([
        'name' => 'Ferrari',
        'base_country' => 'Italy',
        'team_principal' => 'Frederic Vasseur',
    ]);

    $createResponse = $this->postJson('/api/drivers', [
        'team_id' => $team->id,
        'first_name' => 'Charles',
        'last_name' => 'Leclerc',
        'number' => 16,
        'nationality' => 'Monaco',
    ]);

    $createResponse
        ->assertCreated()
        ->assertJsonPath('team.name', 'Ferrari')
        ->assertJsonPath('number', 16);

    $driverId = $createResponse->json('id');

    $updateResponse = $this->putJson("/api/drivers/{$driverId}", [
        'number' => 99,
    ]);

    $updateResponse
        ->assertOk()
        ->assertJsonPath('number', 99);

    expect(Driver::query()->find($driverId)?->number)->toBe(99);
});

test('it can delete teams and drivers', function () {
    $team = Team::create([
        'name' => 'Red Bull Racing',
    ]);

    $driver = Driver::create([
        'team_id' => $team->id,
        'first_name' => 'Max',
        'last_name' => 'Verstappen',
        'number' => 1,
    ]);

    $this->deleteJson("/api/drivers/{$driver->id}")
        ->assertNoContent();

    $this->deleteJson("/api/teams/{$team->id}")
        ->assertNoContent();

    $this->assertDatabaseMissing('drivers', ['id' => $driver->id]);
    $this->assertDatabaseMissing('teams', ['id' => $team->id]);
});