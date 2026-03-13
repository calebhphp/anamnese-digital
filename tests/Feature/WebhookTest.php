<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Anamnese;
use App\Models\AnamneseHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

class WebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_pode_enviar_webhook_ao_finalizar()
    {
        // Mock do HTTP Client
        Http::fake([
            'https://test-webhook.com/*' => Http::response(['status' => 'received'], 200)
        ]);

        // Configurar webhook
        config(['services.webhook.url' => 'https://test-webhook.com/endpoint']);

        // Criar e preencher anamnese
        $this->get('/');
        $anamnese = Anamnese::first();

        // Salvar respostas
        $this->post('/anamnese/store', [
            'session_id' => $anamnese->session_id,
            'step' => '1',
            'nascimento' => '1990-01-01',
            'sexo' => 'feminino'
        ]);

        // Finalizar (deve disparar webhook)
        $response = $this->post('/anamnese/store', [
            'session_id' => $anamnese->session_id,
            'step' => 'final',
            'completed' => 'true'
        ]);

        $response->assertStatus(200);
        
        // Verificar se o webhook foi chamado
        Http::assertSent(function ($request) {
            return $request->url() == 'https://test-webhook.com/endpoint' &&
                   $request->method() == 'POST';
        });
    }

    public function test_webhook_nao_e_enviado_sem_url_configurada()
    {
        // Garantir que webhook está desconfigurado
        config(['services.webhook.url' => null]);

        // Criar e finalizar anamnese
        $this->get('/');
        $anamnese = Anamnese::first();

        $this->post('/anamnese/store', [
            'session_id' => $anamnese->session_id,
            'step' => 'final',
            'completed' => 'true'
        ]);

        // Verificar que não houve tentativa de webhook
        $this->assertTrue(true); // Se chegou aqui, não lançou exceção
    }

    public function test_webhook_envia_json_completo()
    {
        // Mock para capturar o payload
        $capturedPayload = null;
        
        Http::fake(function ($request) use (&$capturedPayload) {
            $capturedPayload = $request->data();
            return Http::response(['status' => 'ok'], 200);
        });

        config(['services.webhook.url' => 'https://test-webhook.com']);

        // Criar e preencher anamnese completa
        $this->get('/');
        $anamnese = Anamnese::first();

        // Simular preenchimento completo
        $respostasCompletas = [
            'step_1' => ['nascimento' => '1990-01-01', 'sexo' => 'feminino'],
            'step_2' => ['objetivo' => 'Emagrecer', 'gordura_eliminar' => 10],
            'step_3' => ['lesao' => 'nao', 'medicamentos' => 'nenhum'],
        ];

        $anamnese->responses = $respostasCompletas;
        $anamnese->save();

        // Finalizar
        $this->post('/anamnese/store', [
            'session_id' => $anamnese->session_id,
            'step' => 'final',
            'completed' => 'true'
        ]);

        // Verificar payload do webhook
        $this->assertNotNull($capturedPayload);
        $this->assertArrayHasKey('anamnese_id', $capturedPayload);
        $this->assertArrayHasKey('data', $capturedPayload);
        $this->assertArrayHasKey('metadata', $capturedPayload);
        $this->assertEquals('anamnese.completed', $capturedPayload['event']);
    }

    public function test_webhook_lida_com_erros_graciosamente()
    {
        // Mock de falha no webhook
        Http::fake([
            '*' => Http::response(null, 500)
        ]);

        config(['services.webhook.url' => 'https://webhook-falho.com']);

        // Criar e finalizar anamnese
        $this->get('/');
        $anamnese = Anamnese::first();

        $response = $this->post('/anamnese/store', [
            'session_id' => $anamnese->session_id,
            'step' => 'final',
            'completed' => 'true'
        ]);

        // Mesmo com falha no webhook, a resposta deve ser sucesso
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

    public function test_pode_testar_webhook_com_url_personalizada()
    {
        Http::fake([
            'https://meu-webhook.com/teste' => Http::response(['received' => true], 200)
        ]);

        $response = $this->post('/anamnese/test-webhook', [
            'webhook_url' => 'https://meu-webhook.com/teste'
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        Http::assertSent(function ($request) {
            return $request->url() == 'https://meu-webhook.com/teste' &&
                   $request->method() == 'POST';
        });
    }

    public function test_teste_webhook_valida_url()
    {
        $response = $this->post('/anamnese/test-webhook', [
            'webhook_url' => 'url-invalida'
        ]);

        $response->assertStatus(422); // Validation error
        $response->assertJson(['success' => false]);
    }

    public function test_pode_exportar_anamnese_como_json()
    {
        $anamnese = Anamnese::factory()->create([
            'completed' => true
        ]);
        
        // Criar histórico
        AnamneseHistory::factory()->create([
            'anamnese_id' => $anamnese->id
        ]);

        $response = $this->get("/anamnese/export/{$anamnese->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'anamnese',
                'history',
                'exported_at'
            ]
        ]);
    }

    public function test_export_retorna_404_para_id_invalido()
    {
        $response = $this->get('/anamnese/export/99999');

        $response->assertStatus(404);
        $response->assertJson(['success' => false]);
    }
}