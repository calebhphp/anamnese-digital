<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Anamnese;
use App\Models\AnamneseHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class AnamneseControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pode_acessar_pagina_inicial()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('anamnese');
        $response->assertViewHas('sessionId');
    }

    /** @test */
    public function cria_nova_sessao_ao_acessar()
    {
        $this->get('/');
        
        $this->assertDatabaseCount('anamneses', 1);
        
        $anamnese = Anamnese::first();
        $this->assertNotNull($anamnese->session_id);
        $this->assertEquals(32, strlen($anamnese->session_id));
        $this->assertFalse($anamnese->completed);
    }

    /** @test */
    public function pode_salvar_primeiro_passo()
    {
        // Primeiro, criar uma sessão
        $this->get('/');
        $anamnese = Anamnese::first();

        // Dados do passo 1
        $dados = [
            'session_id' => $anamnese->session_id,
            'step' => '1',
            'nascimento' => '1990-01-01',
            'sexo' => 'feminino',
            'menopausa' => 'nao'
        ];

        $response = $this->post('/anamnese/store', $dados);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // Verificar se salvou
        $anamneseAtualizada = $anamnese->fresh();
        $this->assertArrayHasKey('step_1', $anamneseAtualizada->responses);
        $this->assertEquals('1990-01-01', $anamneseAtualizada->responses['step_1']['nascimento']);
        $this->assertEquals('feminino', $anamneseAtualizada->responses['step_1']['sexo']);
    }

    /** @test */
    public function pode_salvar_multiplos_passos()
    {
        // Criar sessão
        $this->get('/');
        $anamnese = Anamnese::first();

        // Passo 1
        $this->post('/anamnese/store', [
            'session_id' => $anamnese->session_id,
            'step' => '1',
            'nascimento' => '1990-01-01',
            'sexo' => 'feminino',
            'menopausa' => 'nao'
        ]);

        // Passo 2
        $this->post('/anamnese/store', [
            'session_id' => $anamnese->session_id,
            'step' => '2',
            'objetivo' => 'Emagrecer',
            'gordura_eliminar' => '10',
            'impedimentos' => 'Falta de tempo'
        ]);

        $anamneseAtualizada = $anamnese->fresh();
        
        $this->assertArrayHasKey('step_1', $anamneseAtualizada->responses);
        $this->assertArrayHasKey('step_2', $anamneseAtualizada->responses);
        $this->assertEquals('Emagrecer', $anamneseAtualizada->responses['step_2']['objetivo']);
    }

    /** @test */
    public function nao_aceita_sessao_invalida()
    {
        $response = $this->post('/anamnese/store', [
            'session_id' => 'sessao-invalida',
            'step' => '1',
            'nascimento' => '1990-01-01'
        ]);

        $response->assertStatus(404);
        $response->assertJson(['success' => false]);
    }

    /** @test */
    public function pode_finalizar_anamnese()
    {
        // Criar e preencher anamnese
        $this->get('/');
        $anamnese = Anamnese::first();

        // Salvar alguns passos
        $this->post('/anamnese/store', [
            'session_id' => $anamnese->session_id,
            'step' => '1',
            'nascimento' => '1990-01-01',
            'sexo' => 'feminino'
        ]);

        // Finalizar
        $response = $this->post('/anamnese/store', [
            'session_id' => $anamnese->session_id,
            'step' => 'final',
            'completed' => 'true'
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // Verificar se marcou como completo
        $this->assertTrue($anamnese->fresh()->completed);
        
        // Verificar se criou histórico
        $this->assertDatabaseCount('anamnese_histories', 1);
    }

    /** @test */
    public function campos_obrigatorios_sao_validados_no_backend()
    {
        // Teste será implementado quando adicionar validação no backend
        $this->assertTrue(true);
    }
}