<?php

use App\Models\Driver;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('it can create and list teams', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['crud']);

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
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['crud']);

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
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['crud']);

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

test('guest token has read-only permissions', function () {
    Team::create([
        'name' => 'Williams',
        'base_country' => 'United Kingdom',
        'team_principal' => 'James Vowles',
    ]);

    $tokenResponse = $this->postJson('/api/tokens/guest');

    $tokenResponse
        ->assertOk()
        ->assertJsonPath('abilities.0', 'read');

    $token = $tokenResponse->json('token');

    $this->getJson('/api/teams', ['Authorization' => "Bearer {$token}"])
        ->assertOk();

    $this->postJson(
        '/api/teams',
        ['name' => 'Alpine'],
        ['Authorization' => "Bearer {$token}"]
    )->assertForbidden();
});

test('login returns crud token for valid user', function () {
    $user = User::factory()->create([
        'email' => 'admin@example.com',
        'password' => Hash::make('password123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $response
        ->assertOk()
        ->assertJsonPath('abilities.0', 'read')
        ->assertJsonPath('abilities.1', 'crud')
        ->assertJsonPath('user.email', $user->email);
});