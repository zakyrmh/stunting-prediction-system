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

test('kader users see correct calculated stats on the dashboard', function () {
    $posyandu = \App\Models\Posyandu::factory()->create();
    $kader = User::factory()->create([
        'role' => 'kader',
        'posyandu_id' => $posyandu->id,
    ]);

    // 1. Create Children under this Posyandu
    $childWeighed = \App\Models\Children::factory()->create(['posyandu_id' => $posyandu->id]);
    $childNotWeighed = \App\Models\Children::factory()->create(['posyandu_id' => $posyandu->id]);
    $childFaltering = \App\Models\Children::factory()->create(['posyandu_id' => $posyandu->id]);

    // Child in another posyandu to verify isolation
    $otherPosyandu = \App\Models\Posyandu::factory()->create();
    $childOther = \App\Models\Children::factory()->create(['posyandu_id' => $otherPosyandu->id]);

    // 2. Weighed child has prediction today
    $weighedPred = \App\Models\Prediction::factory()->create([
        'child_id' => $childWeighed->id,
        'posyandu_id' => $posyandu->id,
        'examined_at' => now(),
        'weight' => 10.0,
    ]);

    // 3. Faltering child (2T) in this posyandu
    \App\Models\Prediction::factory()->create([
        'child_id' => $childFaltering->id,
        'posyandu_id' => $posyandu->id,
        'weight' => 12.0,
        'examined_at' => now()->subMonths(2),
    ]);
    \App\Models\Prediction::factory()->create([
        'child_id' => $childFaltering->id,
        'posyandu_id' => $posyandu->id,
        'weight' => 11.8,
        'examined_at' => now()->subMonths(1),
    ]);
    \App\Models\Prediction::factory()->create([
        'child_id' => $childFaltering->id,
        'posyandu_id' => $posyandu->id,
        'weight' => 11.5,
        'examined_at' => now(),
    ]);

    // 4. Other posyandu has prediction today (should not count for this kader)
    \App\Models\Prediction::factory()->create([
        'child_id' => $childOther->id,
        'posyandu_id' => $otherPosyandu->id,
        'examined_at' => now(),
        'weight' => 8.5,
    ]);

    $this->actingAs($kader);

    $response = $this->get(route('dashboard'));

    $response->assertOk();
    $response->assertViewHas('kaderData', function ($kaderData) use ($weighedPred) {
        // Weighed today at our posyandu: childWeighed & childFaltering (total 2)
        // Not weighed this month at our posyandu: childNotWeighed (total 1)
        // Faltering count at our posyandu: childFaltering (total 1)
        // Today entries: weighedPred and the faltering today pred (total 2)
        return $kaderData['weighedToday'] === 2
            && $kaderData['notWeighedThisMonth'] === 1
            && $kaderData['growthFalteringCount'] === 1
            && $kaderData['todayEntriesCount'] === 2
            && $kaderData['todayEntries']->pluck('id')->contains($weighedPred->id);
    });
});

test('orang tua users see correct calculated stats and history on the dashboard', function () {
    $parent = User::factory()->create(['role' => 'orang_tua']);
    $posyandu = \App\Models\Posyandu::factory()->create();

    // 1. Create Child for this parent
    $child = \App\Models\Children::factory()->create([
        'user_id' => $parent->id,
        'posyandu_id' => $posyandu->id,
        'name' => 'Adit Pratama',
        'gender' => 'male',
    ]);

    // 2. Add history of measurements
    // 3 measurements
    \App\Models\Prediction::factory()->create([
        'child_id' => $child->id,
        'posyandu_id' => $posyandu->id,
        'age_months' => 12,
        'height' => 74.0,
        'weight' => 9.0,
        'result' => 'normal',
        'examined_at' => now()->subMonths(2),
    ]);
    \App\Models\Prediction::factory()->create([
        'child_id' => $child->id,
        'posyandu_id' => $posyandu->id,
        'age_months' => 13,
        'height' => 75.0,
        'weight' => 9.2,
        'result' => 'stunting_risk',
        'examined_at' => now()->subMonths(1),
    ]);
    $latestPred = \App\Models\Prediction::factory()->create([
        'child_id' => $child->id,
        'posyandu_id' => $posyandu->id,
        'age_months' => 14,
        'height' => 75.5,
        'weight' => 9.3,
        'result' => 'stunted',
        'examined_at' => now(),
    ]);

    // 3. Add intervention for the latest prediction
    $intervention = \App\Models\Intervention::factory()->create([
        'prediction_id' => $latestPred->id,
        'recommendation' => 'Pemberian PMT telur dan susu khusus balita stunted.',
    ]);

    $this->actingAs($parent);

    $response = $this->get(route('dashboard'));

    $response->assertOk();
    $response->assertViewHas('parentData', function ($parentData) use ($child, $latestPred, $intervention) {
        return $parentData['children']->count() === 1
            && $parentData['selectedChild']->id === $child->id
            && $parentData['latestPrediction']->id === $latestPred->id
            && $parentData['latestIntervention']->id === $intervention->id
            && $parentData['growthPoints']->count() === 3
            // Check computed coordinates:
            // For count = 3: index 0 (x=40), index 1 (x=210), index 2 (x=380)
            && $parentData['growthPoints'][0]['x'] == 40.0
            && $parentData['growthPoints'][1]['x'] == 210.0
            && $parentData['growthPoints'][2]['x'] == 380.0
            // Height 75.5 maps to y between 160 (75cm) and 110 (78cm):
            // y = 160 - ((75.5 - 75) / 3) * 50 = 160 - 8.33 = 151.7
            && $parentData['growthPoints'][2]['y'] == 151.7
            && $parentData['pathD'] === 'M 40 168 L 210 160 L 380 151.7';
    });
});