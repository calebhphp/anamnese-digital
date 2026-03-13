<?php

namespace App\Http\Controllers;

use App\Models\Anamnese;
use App\Models\AnamneseHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

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
                AnamneseHistory::create([
                    'anamnese_id' => $anamnese->id,
                    'responses' => $responses
                ]);
                
                // Disparar webhook em background para não travar a resposta
                if (config('services.webhook.url')) {
                    $this->dispatchWebhook($responses);
                }
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
    
    private function dispatchWebhook($data)
    {
        try {
            $client = new \GuzzleHttp\Client([
                'timeout' => 5,
                'verify' => false // Apenas para desenvolvimento
            ]);
            
            $client->postAsync(config('services.webhook.url'), [
                'json' => [
                    'data' => $data,
                    'timestamp' => now()->toIso8601String(),
                    'event' => 'anamnese.completed'
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro no webhook: ' . $e->getMessage());
        }
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