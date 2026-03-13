<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Anamnese;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

class CompleteFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_fluxo_completo_do_usuario()
    {
        // Mock webhook
        Http::fake([
            'https://test-webhook.com/*' => Http::response(['status' => 'ok'], 200)
        ]);

        config(['services.webhook.url' => 'https://test-webhook.com/endpoint']);

        // 1. Usuário acessa a página
        $response = $this->get('/');
        $response->assertStatus(200);
        
        $anamnese = Anamnese::first();
        $sessionId = $anamnese->session_id;

        // 2. Preenche passo 1 (Dados Pessoais)
        $this->post('/anamnese/store', [
            'session_id' => $sessionId,
            'step' => '1',
            'nascimento' => '1985-03-15',
            'sexo' => 'feminino',
            'menopausa' => 'nao'
        ]);

        // 3. Preenche passo 2 (Objetivos)
        $this->post('/anamnese/store', [
            'session_id' => $sessionId,
            'step' => '2',
            'objetivo' => 'Perder peso e ganhar massa muscular',
            'gordura_eliminar' => '12.5',
            'impedimentos' => 'Falta de disciplina e tempo'
        ]);

        // 4. Preenche passo 3 (Saúde)
        $this->post('/anamnese/store', [
            'session_id' => $sessionId,
            'step' => '3',
            'lesao' => 'nao',
            'detalhes_lesao' => 'Nenhuma lesão',
            'medicamentos' => 'Nenhum'
        ]);

        // 5. Preenche passo 4 (Hábitos)
        $this->post('/anamnese/store', [
            'session_id' => $sessionId,
            'step' => '4',
            'refeicoes' => '5',
            'tempo_treino' => '1h',
            'horario_treino' => '18:00'
        ]);

        // 6. Preenche passo 5 (Local)
        $this->post('/anamnese/store', [
            'session_id' => $sessionId,
            'step' => '5',
            'local_treino' => 'academia',
            'tempo_deslocamento' => '30min'
        ]);

        // 7. Preenche passo 6 (Experiência)
        $this->post('/anamnese/store', [
            'session_id' => $sessionId,
            'step' => '6',
            'experiencia_personal' => 'nao',
            'nivel_exercicios' => 'intermediario'
        ]);

        // 8. Preenche passo 7 (Medidas 1)
        $this->post('/anamnese/store', [
            'session_id' => $sessionId,
            'step' => '7',
            'peso' => '75.5',
            'altura' => '1.68',
            'biceps' => '32',
            'coxa' => '58'
        ]);

        // 9. Preenche passo 8 (Medidas 2)
        $this->post('/anamnese/store', [
            'session_id' => $sessionId,
            'step' => '8',
            'busto' => '95',
            'cintura' => '78',
            'quadril' => '102'
        ]);

        // 10. Preenche passo 9 (Referência)
        $this->post('/anamnese/store', [
            'session_id' => $sessionId,
            'step' => '9',
            'conheceu' => 'instagram',
            'comentario' => 'Ansiosa para começar!'
        ]);

        // 11. Finalizar
        $response = $this->post('/anamnese/store', [
            'session_id' => $sessionId,
            'step' => 'final',
            'completed' => 'true'
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // Verificações finais
        $anamneseFinal = $anamnese->fresh();
        
        // Verificar se todos os passos foram salvos
        $this->assertArrayHasKey('step_1', $anamneseFinal->responses);
        $this->assertArrayHasKey('step_2', $anamneseFinal->responses);
        $this->assertArrayHasKey('step_3', $anamneseFinal->responses);
        $this->assertArrayHasKey('step_4', $anamneseFinal->responses);
        $this->assertArrayHasKey('step_5', $anamneseFinal->responses);
        $this->assertArrayHasKey('step_6', $anamneseFinal->responses);
        $this->assertArrayHasKey('step_7', $anamneseFinal->responses);
        $this->assertArrayHasKey('step_8', $anamneseFinal->responses);
        $this->assertArrayHasKey('step_9', $anamneseFinal->responses);

        // Verificar se marcou como completo
        $this->assertTrue($anamneseFinal->completed);

        // Verificar se criou histórico
        $this->assertDatabaseCount('anamnese_histories', 1);

        // Verificar se webhook foi chamado
        Http::assertSent(function ($request) {
            return $request->url() == 'https://test-webhook.com/endpoint';
        });
    }

    public function test_fluxo_com_webhook_personalizado()
    {
        // Mock para capturar webhook
        $webhookCalled = false;
        $webhookUrl = 'https://kinbox.com.br/webhook/123';
        
        Http::fake(function ($request) use ($webhookUrl, &$webhookCalled) {
            if ($request->url() == $webhookUrl) {
                $webhookCalled = true;
                return Http::response(['status' => 'ok'], 200);
            }
            return Http::response(null, 404);
        });

        // Configurar webhook no momento do teste
        config(['services.webhook.url' => $webhookUrl]);

        // Criar e finalizar anamnese
        $this->get('/');
        $anamnese = Anamnese::first();

        // Finalizar
        $this->post('/anamnese/store', [
            'session_id' => $anamnese->session_id,
            'step' => 'final',
            'completed' => 'true'
        ]);

        // Verificar se webhook foi chamado para a URL correta
        $this->assertTrue($webhookCalled);
        
        Http::assertSent(function ($request) use ($webhookUrl) {
            return $request->url() == $webhookUrl;
        });
    }
}