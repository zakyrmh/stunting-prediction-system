<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
});

test('bidan users can visit the dashboard and see stats', function () {
    $user = User::factory()->create(['role' => 'bidan']);
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
    $response->assertViewHas('bidanData');
});

test('bidan users see correct calculated stats on the dashboard', function () {
    $bidan = User::factory()->create(['role' => 'bidan']);
    $posyandu = \App\Models\Posyandu::factory()->create();

    // 1. Create Children
    $childNormal = \App\Models\Children::factory()->create(['posyandu_id' => $posyandu->id]);
    $childStunted = \App\Models\Children::factory()->create(['posyandu_id' => $posyandu->id]);
    $childFaltering = \App\Models\Children::factory()->create(['posyandu_id' => $posyandu->id]);

    // 2. Add predictions for normal child
    \App\Models\Prediction::factory()->create([
        'child_id' => $childNormal->id,
        'posyandu_id' => $posyandu->id,
        'result' => 'normal',
        'examined_at' => now(),
    ]);

    // 3. Add predictions for stunted child (latest is stunted)
    \App\Models\Prediction::factory()->create([
        'child_id' => $childStunted->id,
        'posyandu_id' => $posyandu->id,
        'result' => 'normal',
        'examined_at' => now()->subMonths(1),
    ]);
    $latestStuntedPred = \App\Models\Prediction::factory()->create([
        'child_id' => $childStunted->id,
        'posyandu_id' => $posyandu->id,
        'result' => 'stunted',
        'examined_at' => now(),
    ]);

    // 4. Add predictions for growth faltering child (2T: flat or down weight twice)
    // Predictions from oldest to newest: 10.0kg -> 9.8kg -> 9.5kg
    \App\Models\Prediction::factory()->create([
        'child_id' => $childFaltering->id,
        'posyandu_id' => $posyandu->id,
        'weight' => 10.0,
        'examined_at' => now()->subMonths(2),
        'result' => 'normal',
    ]);
    \App\Models\Prediction::factory()->create([
        'child_id' => $childFaltering->id,
        'posyandu_id' => $posyandu->id,
        'weight' => 9.8,
        'examined_at' => now()->subMonths(1),
        'result' => 'normal',
    ]);
    \App\Models\Prediction::factory()->create([
        'child_id' => $childFaltering->id,
        'posyandu_id' => $posyandu->id,
        'weight' => 9.5,
        'examined_at' => now(),
        'result' => 'normal',
    ]);

    // 5. Add pending verification (intervention with status pending)
    $intervention = \App\Models\Intervention::factory()->create([
        'prediction_id' => $latestStuntedPred->id,
        'status' => 'pending',
    ]);

    $this->actingAs($bidan);

    $response = $this->get(route('dashboard'));

    $response->assertOk();
    $response->assertViewHas('bidanData', function ($bidanData) use ($childStunted, $intervention) {
        return $bidanData['totalChildren'] === 3
            && $bidanData['stuntedCount'] === 1
            && $bidanData['stuntedPercentage'] == 33.3
            && $bidanData['growthFalteringCount'] === 1
            && $bidanData['growthFalteringPercentage'] == 33.3
            && $bidanData['pendingVerificationsCount'] === 1
            && $bidanData['pendingVerifications']->first()->id === $intervention->id;
    });
});