<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnamneseHistory extends Model
{
    use HasFactory;

    protected $table = 'anamnese_histories';
    
    protected $fillable = [
        'anamnese_id',
        'responses'
    ];

    protected $casts = [
        'responses' => 'array'
    ];

    // Relacionamento com anamnese
    public function anamnese()
    {
        return $this->belongsTo(Anamnese::class);
    }
}