<?php

use App\Models\User;
use App\Models\Posyandu;
use App\Models\Children;

test('bidan cannot access create balita page', function () {
    $bidan = User::factory()->create(['role' => 'bidan']);
    $this->actingAs($bidan);

    $response = $this->get(route('balita.form'));
    $response->assertStatus(403);
});

test('bidan cannot store new balita', function () {
    $bidan = User::factory()->create(['role' => 'bidan']);
    $posyandu = Posyandu::factory()->create();
    $this->actingAs($bidan);

    $response = $this->post(route('balita.store'), [
        'name' => 'Test Balita',
        'nik' => '1234567890123456',
        'birth_date' => now()->subYears(2)->toDateString(),
        'birth_place' => 'Jakarta',
        'gender' => 'male',
        'address' => 'Test Address',
        'posyandu_id' => $posyandu->id,
    ]);

    $response->assertStatus(403);
    $this->assertDatabaseMissing('children', [
        'name' => 'Test Balita',
    ]);
});

test('kader can access create balita page', function () {
    $posyandu = Posyandu::factory()->create();
    $kader = User::factory()->create([
        'role' => 'kader',
        'posyandu_id' => $posyandu->id,
    ]);
    $this->actingAs($kader);

    $response = $this->get(route('balita.form'));
    $response->assertStatus(200);
});

test('kader can store new balita', function () {
    $posyandu = Posyandu::factory()->create();
    $kader = User::factory()->create([
        'role' => 'kader',
        'posyandu_id' => $posyandu->id,
    ]);
    $this->actingAs($kader);

    $response = $this->post(route('balita.store'), [
        'name' => 'Test Balita Kader',
        'nik' => '9876543210987654',
        'birth_date' => now()->subYears(2)->toDateString(),
        'birth_place' => 'Jakarta',
        'gender' => 'male',
        'address' => 'Test Address',
        'posyandu_id' => $posyandu->id,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('children', [
        'name' => 'Test Balita Kader',
        'nik' => '9876543210987654',
        'posyandu_id' => $posyandu->id,
    ]);
});
