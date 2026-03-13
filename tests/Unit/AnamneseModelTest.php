<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Anamnese;
use App\Models\AnamneseHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AnamneseModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_pode_criar_uma_anamnese()
    {
        $anamnese = Anamnese::create([
            'session_id' => 'test-session-123',
            'responses' => ['step_1' => ['teste' => 'dados']],
            'completed' => false
        ]);

        $this->assertDatabaseHas('anamneses', [
            'session_id' => 'test-session-123',
            'completed' => false
        ]);
        
        $this->assertIsArray($anamnese->responses);
        $this->assertEquals('dados', $anamnese->responses['step_1']['teste']);
    }

    public function test_session_id_deve_ser_unico()
    {
        Anamnese::create([
            'session_id' => 'unique-123',
            'responses' => []
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Anamnese::create([
            'session_id' => 'unique-123',
            'responses' => []
        ]);
    }

    public function test_pode_marcar_como_completada()
    {
        $anamnese = Anamnese::factory()->create([
            'completed' => false
        ]);

        $this->assertFalse($anamnese->completed);
        
        $anamnese->update(['completed' => true]);
        
        $this->assertTrue($anamnese->fresh()->completed);
    }

    public function test_pode_adicionar_respostas()
    {
        $anamnese = Anamnese::factory()->create();
        
        $novasRespostas = [
            'step_3' => [
                'lesao' => 'nao',
                'medicamentos' => 'nenhum',
                'saved_at' => now()->toDateTimeString()
            ]
        ];
        
        $anamnese->responses = array_merge($anamnese->responses ?? [], $novasRespostas);
        $anamnese->save();
        
        $this->assertArrayHasKey('step_3', $anamnese->fresh()->responses);
        $this->assertEquals('nao', $anamnese->fresh()->responses['step_3']['lesao']);
    }

    public function test_pode_ter_historico()
    {
        $anamnese = Anamnese::factory()->create();
        
        $history = AnamneseHistory::create([
            'anamnese_id' => $anamnese->id,
            'responses' => ['teste' => 'historico']
        ]);
        
        // Refresh the model to load the relationship
        $anamnese = $anamnese->fresh();
        
        $this->assertNotNull($anamnese->history);
        $this->assertInstanceOf(AnamneseHistory::class, $anamnese->history->first());
        $this->assertEquals('historico', $anamnese->history->first()->responses['teste']);
    }
}