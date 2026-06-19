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
