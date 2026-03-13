<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anamnese extends Model
{
    use HasFactory;

    protected $table = 'anamneses';
    
    protected $fillable = [
        'session_id',
        'responses',
        'completed'
    ];

    protected $casts = [
        'responses' => 'array',
        'completed' => 'boolean'
    ];

    // Relacionamento com histórico
    public function histories()
    {
        return $this->hasMany(AnamneseHistory::class);
    }

    // Pegar última resposta
    public function getLastResponseAttribute()
    {
        return $this->histories()->latest()->first();
    }
}