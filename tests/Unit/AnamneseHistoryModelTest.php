<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Anamnese;
use App\Models\AnamneseHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AnamneseHistoryModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pode_criar_historico()
    {
        $anamnese = Anamnese::factory()->create();
        
        $history = AnamneseHistory::create([
            'anamnese_id' => $anamnese->id,
            'responses' => ['passo_1' => ['dado' => 'valor']]
        ]);

        $this->assertDatabaseHas('anamnese_histories', [
            'anamnese_id' => $anamnese->id
        ]);
        
        $this->assertEquals('valor', $history->responses['passo_1']['dado']);
    }

    /** @test */
    public function history_belongs_to_anamnese()
    {
        $anamnese = Anamnese::factory()->create();
        $history = AnamneseHistory::factory()->create([
            'anamnese_id' => $anamnese->id
        ]);

        $this->assertInstanceOf(Anamnese::class, $history->anamnese);
        $this->assertEquals($anamnese->id, $history->anamnese->id);
    }
}