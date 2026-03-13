<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Anamnese;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ValidationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function dados_do_passo_sao_preservados_entre_saves()
    {
        $this->get('/');
        $anamnese = Anamnese::first();

        // Salvar passo 1
        $this->post('/anamnese/store', [
            'session_id' => $anamnese->session_id,
            'step' => '1',
            'nascimento' => '1990-01-01',
            'sexo' => 'feminino'
        ]);

        // Salvar passo 2
        $this->post('/anamnese/store', [
            'session_id' => $anamnese->session_id,
            'step' => '2',
            'objetivo' => 'Emagrecer'
        ]);

        // Verificar se passo 1 ainda existe
        $anamneseAtualizada = $anamnese->fresh();
        $this->assertEquals('1990-01-01', $anamneseAtualizada->responses['step_1']['nascimento']);
        $this->assertEquals('Emagrecer', $anamneseAtualizada->responses['step_2']['objetivo']);
    }

    /** @test */
    public function sessao_sobrescreve_dados_do_mesmo_passo()
    {
        $this->get('/');
        $anamnese = Anamnese::first();

        // Salvar passo 1 primeira vez
        $this->post('/anamnese/store', [
            'session_id' => $anamnese->session_id,
            'step' => '1',
            'nascimento' => '1990-01-01'
        ]);

        // Salvar passo 1 novamente com dados diferentes
        $this->post('/anamnese/store', [
            'session_id' => $anamnese->session_id,
            'step' => '1',
            'nascimento' => '1995-05-05'
        ]);

        $anamneseAtualizada = $anamnese->fresh();
        $this->assertEquals('1995-05-05', $anamneseAtualizada->responses['step_1']['nascimento']);
    }
}