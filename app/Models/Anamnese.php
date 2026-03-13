<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anamnese extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'responses',
        'completed'
    ];

    protected $casts = [
        'responses' => 'array',
        'completed' => 'boolean'
    ];

    /**
     * Get the history records for the anamnese.
     */
    public function history()
    {
        return $this->hasMany(AnamneseHistory::class);
    }
}