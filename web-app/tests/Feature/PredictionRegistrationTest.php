<?php

use App\Models\User;
use App\Models\Posyandu;

test('bidan cannot access create prediction page', function () {
    $bidan = User::factory()->create(['role' => 'bidan']);
    $this->actingAs($bidan);

    $response = $this->get(route('prediksi.form'));
    $response->assertStatus(403);
});

test('kader can access create prediction page', function () {
    $posyandu = Posyandu::factory()->create();
    $kader = User::factory()->create([
        'role' => 'kader',
        'posyandu_id' => $posyandu->id,
    ]);
    $this->actingAs($kader);

    $response = $this->get(route('prediksi.form'));
    $response->assertStatus(200);
});

test('kader can submit monthly prediction form successfully', function () {
    $posyandu = Posyandu::factory()->create();
    $kader = User::factory()->create([
        'role' => 'kader',
        'posyandu_id' => $posyandu->id,
    ]);
    
    $child = \App\Models\Children::factory()->create([
        'posyandu_id' => $posyandu->id,
        'birth_date' => now()->subYears(2),
    ]);

    $this->actingAs($kader);

    \Livewire\Volt\Volt::test('prediction.form')
        ->set('child_id', $child->id)
        ->set('weight', 12.5)
        ->set('height', 85.5)
        ->set('gejala.R04', '1.0')
        ->set('gejala.R07', '0.6')
        ->set('notes', 'Catatan kader test')
        ->call('save')
        ->assertHasNoErrors()
        ->assertSet('showResult', true);

    $this->assertDatabaseHas('predictions', [
        'child_id' => $child->id,
        'recorded_by' => $kader->id,
        'notes' => 'Catatan kader test',
    ]);

    // Check if an intervention is logged due to non-normal status from R04/R07 symptoms
    $this->assertDatabaseHas('interventions', [
        'status' => 'pending',
    ]);
});
