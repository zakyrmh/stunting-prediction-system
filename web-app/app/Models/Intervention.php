<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['prediction_id', 'recommendation', 'status', 'follow_up_date', 'follow_up_notes', 'handled_by'])]
class Intervention extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'follow_up_date' => 'date',
        ];
    }

    public function prediction(): BelongsTo
    {
        return $this->belongsTo(Prediction::class);
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }
}
