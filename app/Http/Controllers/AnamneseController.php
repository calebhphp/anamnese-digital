<?php

namespace App\Http\Controllers;

use App\Models\Anamnese;
use App\Models\AnamneseHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class AnamneseController extends Controller
{
    public function index()
    {
        try {
            // Gerar session_id único
            $sessionId = Str::random(32);
            
            // Criar nova sessão
            Anamnese::create([
                'session_id' => $sessionId,
                'responses' => [],
                'completed' => false
            ]);
            
            return view('anamnese', ['sessionId' => $sessionId]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao criar sessão: ' . $e->getMessage());
            return back()->with('error', 'Erro ao iniciar a anamnese. Tente novamente.');
        }
    }
    
    public function store(Request $request)
    {
        try {
            // Validar request
            $request->validate([
                'session_id' => 'required|string',
                'step' => 'required|string'
            ]);
            
            // Buscar anamnese
            $anamnese = Anamnese::where('session_id', $request->session_id)->first();
            
            if (!$anamnese) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sessão não encontrada'
                ], 404);
            }
            
            // Pegar respostas existentes ou inicializar array vazio
            $responses = $anamnese->responses ?? [];
            
            // Pegar todos os dados do formulário exceto os que não queremos
            $formData = $request->except(['_token', 'session_id', 'step', 'completed']);
            
            // Adicionar timestamp
            $formData['saved_at'] = now()->toDateTimeString();
            
            // Atualizar respostas para o step atual
            $responses['step_' . $request->step] = $formData;
            
            // Atualizar o modelo
            $anamnese->responses = $responses;
            
            // Verificar se é a finalização
            if ($request->has('completed') && $request->completed == 'true') {
                $anamnese->completed = true;
                
                // Salvar histórico
                $history = AnamneseHistory::create([
                    'anamnese_id' => $anamnese->id,
                    'responses' => $responses
                ]);
                
                // Disparar webhook se houver URL configurada
                $this->dispatchWebhook($responses, $anamnese->id);
            }
            
            $anamnese->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Respostas salvas com sucesso'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao salvar anamnese: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Endpoint para teste de webhook
     * Pode ser usado para validar a integração
     */
    public function testWebhook(Request $request)
    {
        try {
            $request->validate([
                'webhook_url' => 'required|url'
            ]);
            
            // Dados de teste
            $testData = [
                'test' => true,
                'message' => 'Teste de webhook da Anamnese Digital',
                'timestamp' => now()->toIso8601String(),
                'data' => [
                    'nome' => 'Teste',
                    'email' => 'teste@example.com'
                ]
            ];
            
            // Enviar teste
            $response = Http::timeout(5)
                ->post($request->webhook_url, $testData);
            
            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Webhook testado com sucesso',
                    'response' => $response->json()
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro no webhook: ' . $response->status(),
                    'response' => $response->body()
                ], 400);
            }
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'URL inválida',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erro ao testar webhook: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Exportar dados da anamnese em JSON
     */
    public function export($id)
    {
        try {
            $anamnese = Anamnese::with('history')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'anamnese' => [
                        'id' => $anamnese->id,
                        'session_id' => $anamnese->session_id,
                        'responses' => $anamnese->responses,
                        'completed' => $anamnese->completed,
                        'created_at' => $anamnese->created_at,
                        'updated_at' => $anamnese->updated_at
                    ],
                    'history' => $anamnese->history->map(function($item) {
                        return [
                            'id' => $item->id,
                            'responses' => $item->responses,
                            'created_at' => $item->created_at
                        ];
                    }),
                    'exported_at' => now()->toIso8601String()
                ]
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Anamnese não encontrada'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Erro ao exportar anamnese: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao exportar: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Dispara webhook com os dados da anamnese
     * O URL pode ser configurado via .env ou passado como parâmetro
     */
    private function dispatchWebhook($data, $anamneseId)
    {
        // URL do webhook - pode vir do .env ou ser configurada posteriormente
        $webhookUrl = config('services.webhook.url');
        
        // Se não tiver URL configurada, apenas loga
        if (!$webhookUrl) {
            Log::info('Webhook não configurado. Dados da anamnese ' . $anamneseId . ' prontos para envio.');
            return;
        }
        
        try {
            // Preparar payload completo
            $payload = [
                'anamnese_id' => $anamneseId,
                'timestamp' => now()->toIso8601String(),
                'event' => 'anamnese.completed',
                'data' => $this->formatResponses($data),
                'metadata' => [
                    'version' => '1.0',
                    'source' => 'anamnese-digital',
                    'environment' => app()->environment()
                ]
            ];
            
            // Log do payload para debug
            Log::info('Enviando webhook para: ' . $webhookUrl, ['payload' => $payload]);
            
            // Enviar webhook de forma assíncrona
            Http::timeout(10)
                ->retry(3, 100)
                ->post($webhookUrl, $payload);
            
            Log::info('Webhook enviado com sucesso para anamnese: ' . $anamneseId);
            
        } catch (\Exception $e) {
            Log::error('Erro no webhook: ' . $e->getMessage(), [
                'anamnese_id' => $anamneseId,
                'url' => $webhookUrl
            ]);
        }
    }
    
    /**
     * Formata as respostas para um formato mais amigável
     */
    private function formatResponses($responses)
    {
        $formatted = [];
        
        foreach ($responses as $key => $value) {
            // Extrair número do passo
            if (strpos($key, 'step_') === 0) {
                $stepNumber = str_replace('step_', '', $key);
                $formatted['passo_' . $stepNumber] = $value;
            } else {
                $formatted[$key] = $value;
            }
        }
        
        return $formatted;
    }
    
    // Método opcional para recuperar uma anamnese incompleta
    public function resume($sessionId)
    {
        $anamnese = Anamnese::where('session_id', $sessionId)
            ->where('completed', false)
            ->first();
        
        if (!$anamnese) {
            return redirect()->route('anamnese.index');
        }
        
        return view('anamnese', [
            'sessionId' => $sessionId,
            'savedData' => $anamnese->responses
        ]);
    }
}