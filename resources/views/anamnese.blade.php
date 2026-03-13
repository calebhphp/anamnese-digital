<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Anamnese Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        /* Tema Claro (padrão) */
        :root {
            --bg-primary: #f8f9fa;
            --bg-secondary: #ffffff;
            --bg-card: #ffffff;
            --text-primary: #2d3436;
            --text-secondary: #636e72;
            --accent: #ff6b35;
            --accent-hover: #ff8255;
            --border-color: #dfe6e9;
            --input-bg: #ffffff;
            --input-border: #b2bec3;
            --shadow-color: rgba(0, 0, 0, 0.1);
            --card-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            --progress-bg: #f1f3f5;
            --option-bg: #f8f9fa;
            --option-hover: #fff5f0;
        }

        /* Tema Escuro */
        [data-theme="dark"] {
            --bg-primary: #1a1a1a;
            --bg-secondary: #2d2d2d;
            --bg-card: #2d2d2d;
            --text-primary: #f5f5f5;
            --text-secondary: #cccccc;
            --accent: #ff6b35;
            --accent-hover: #ff8255;
            --border-color: #404040;
            --input-bg: #333333;
            --input-border: #404040;
            --shadow-color: rgba(0, 0, 0, 0.3);
            --card-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            --progress-bg: #333333;
            --option-bg: #333333;
            --option-hover: #3a3a3a;
        }

        /* Estilos gerais */
        body {
            background: var(--bg-primary);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-primary);
            transition: background-color 0.3s ease, color 0.2s ease;
        }

        /* Container principal */
        .container-custom {
            max-width: 600px;
            margin: 20px auto;
            padding: 0 15px;
        }

        /* Card principal */
        .card {
            border: 1px solid var(--border-color);
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            background: var(--bg-card);
            backdrop-filter: blur(10px);
            overflow: hidden;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .card-header {
            background: linear-gradient(135deg, var(--bg-secondary), var(--bg-primary));
            color: var(--text-primary);
            padding: 25px 20px;
            border-bottom: 2px solid var(--accent);
            transition: background-color 0.3s ease;
        }

        .card-header h2 {
            color: var(--text-primary);
            font-weight: 600;
            margin-bottom: 10px;
        }

        .card-header h2 i {
            color: var(--accent);
            margin-right: 10px;
        }

        .card-header p {
            color: var(--text-secondary);
            font-size: 1.1rem;
        }

        .card-body {
            background: var(--bg-card);
            padding: 25px;
            transition: background-color 0.3s ease;
        }

        /* Barra de progresso */
        .progress {
            height: 10px;
            border-radius: 5px;
            margin: 20px 0;
            background: var(--progress-bg);
            border: 1px solid var(--border-color);
        }

        .progress-bar {
            background: var(--accent);
            border-radius: 5px;
            transition: width 0.3s ease;
        }

        /* Indicador de passo */
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            color: var(--text-secondary);
            font-size: 0.95rem;
            font-weight: 500;
        }

        .step-indicator span:first-child {
            color: var(--accent);
            font-weight: 600;
        }

        /* Cards de pergunta */
        .question-card {
            animation: fadeIn 0.5s;
            padding: 10px 0;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Labels */
        .form-label {
            color: var(--text-primary);
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 1rem;
            transition: color 0.2s ease;
        }

        /* Inputs e selects */
        .form-control, .form-select {
            background: var(--input-bg);
            border: 2px solid var(--input-border);
            border-radius: 12px;
            padding: 12px 15px;
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-control:focus, .form-select:focus {
            background: var(--input-bg);
            border-color: var(--accent);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
            color: var(--text-primary);
        }

        .form-control::placeholder {
            color: #888;
            opacity: 1;
        }

        .form-select option {
            background: var(--bg-secondary);
            color: var(--text-primary);
        }

        /* Inputs de data e time */
        input[type="date"]::-webkit-calendar-picker-indicator,
        input[type="time"]::-webkit-calendar-picker-indicator {
            filter: var(--calendar-icon-filter, invert(0));
            opacity: 0.6;
            cursor: pointer;
        }

        [data-theme="dark"] input[type="date"]::-webkit-calendar-picker-indicator,
        [data-theme="dark"] input[type="time"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }

        /* Botões de navegação */
        .btn-navigation {
            background: var(--accent);
            border: none;
            color: white;
            padding: 14px 35px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
        }

        .btn-navigation:hover {
            background: var(--accent-hover);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 107, 53, 0.4);
        }

        .btn-navigation:disabled {
            background: var(--border-color);
            box-shadow: none;
            transform: none;
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-navigation i {
            font-size: 1.1rem;
        }

        /* Painel de controles */
        .controls-panel {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 1000;
        }

        .control-btn {
            width: 50px;
            height: 50px;
            border: 2px solid var(--accent);
            background: var(--bg-card);
            color: var(--text-primary);
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 5px 15px var(--shadow-color);
        }

        .control-btn:hover {
            background: var(--accent);
            color: white;
            transform: scale(1.1);
        }

        .control-btn.active {
            background: var(--accent);
            color: white;
        }

        .font-controls {
            display: flex;
            gap: 5px;
            background: var(--bg-card);
            padding: 5px;
            border-radius: 30px;
            border: 1px solid var(--border-color);
        }

        .font-btn {
            width: 40px;
            height: 40px;
            border: none;
            background: transparent;
            color: var(--text-primary);
            border-radius: 50%;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .font-btn:hover {
            background: var(--accent);
            color: white;
        }

        .font-btn.active {
            background: var(--accent);
            color: white;
        }

        /* Cards de medidas */
        .measurement-input {
            background: var(--option-bg);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 15px;
            border: 1px solid var(--border-color);
            transition: background-color 0.3s ease;
        }

        .measurement-input .form-label {
            color: var(--accent);
            font-weight: 600;
        }

        /* Opções estilo card */
        .option-card {
            border: 2px solid var(--border-color);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.3s;
            background: var(--option-bg);
            color: var(--text-primary);
        }

        .option-card:hover {
            border-color: var(--accent);
            background: var(--option-hover);
            transform: translateX(5px);
        }

        .option-card.selected {
            border-color: var(--accent);
            background: var(--option-hover);
            box-shadow: 0 5px 15px rgba(255, 107, 53, 0.2);
        }

        /* Tamanhos de fonte */
        .font-small { 
            font-size: 14px; 
        }
        .font-small .form-label { font-size: 14px; }
        .font-small .btn-navigation { font-size: 14px; }
        
        .font-medium { 
            font-size: 16px; 
        }
        .font-medium .form-label { font-size: 16px; }
        .font-medium .btn-navigation { font-size: 16px; }
        
        .font-large { 
            font-size: 18px; 
        }
        .font-large .form-label { font-size: 18px; }
        .font-large .btn-navigation { font-size: 18px; }
        .font-large .card-header h2 { font-size: 2rem; }

        /* Modais */
        .modal-content {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 20px;
        }

        .modal-header {
            border-bottom-color: var(--border-color);
        }

        .modal-header h5 {
            color: var(--text-primary);
        }

        .modal-header .btn-close {
            filter: var(--close-icon-filter, invert(0));
        }

        [data-theme="dark"] .modal-header .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        .modal-body {
            color: var(--text-primary);
        }

        .modal-footer {
            border-top-color: var(--border-color);
        }

        /* Cores de texto */
        h1, h2, h3, h4, h5, h6 {
            color: var(--text-primary);
        }

        .text-muted {
            color: var(--text-secondary) !important;
        }

        /* Botões de ação */
        .btn-success {
            background: var(--accent);
            border-color: var(--accent);
            color: white;
        }

        .btn-success:hover {
            background: var(--accent-hover);
            border-color: var(--accent-hover);
            color: white;
        }

        .btn-outline-secondary {
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .btn-outline-secondary:hover {
            background: var(--bg-secondary);
            border-color: var(--accent);
            color: var(--accent);
        }

        /* Indicador de salvamento */
        #savingIndicator {
            background: var(--bg-card) !important;
            color: var(--text-primary) !important;
            border: 1px solid var(--border-color);
            box-shadow: 0 5px 20px var(--shadow-color);
        }

        /* Notificações */
        .notification {
            background: var(--bg-card);
            color: var(--text-primary);
            border-left: 4px solid var(--accent);
            box-shadow: 0 5px 20px var(--shadow-color);
        }

        .notification.alert-warning {
            background: #fff3cd;
            color: #856404;
            border-left-color: #ffc107;
        }

        [data-theme="dark"] .notification.alert-warning {
            background: #332d1a;
            color: #ffd970;
        }

        .notification.alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-left-color: #dc3545;
        }

        [data-theme="dark"] .notification.alert-danger {
            background: #331f1f;
            color: #ff8a8a;
        }

        /* Validação */
        .is-invalid {
            border-color: var(--accent) !important;
        }

        .invalid-feedback {
            color: var(--accent);
            font-size: 0.875rem;
            margin-top: 5px;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-primary);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--accent);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--accent-hover);
        }

        /* Links */
        a {
            color: var(--accent);
        }

        a:hover {
            color: var(--accent-hover);
        }

        /* Checkboxes e radios */
        .form-check-input {
            background-color: var(--input-bg);
            border-color: var(--border-color);
        }

        .form-check-input:checked {
            background-color: var(--accent);
            border-color: var(--accent);
        }

        .form-check-label {
            color: var(--text-primary);
        }

        /* Divisores */
        hr {
            border-color: var(--border-color);
            opacity: 0.3;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .container-custom {
                margin: 10px;
                padding: 0;
            }
            
            .controls-panel {
                position: sticky;
                top: 10px;
                margin-bottom: 15px;
                justify-content: flex-end;
            }
            
            .card-header {
                padding: 20px 15px;
            }
            
            .card-header h2 {
                font-size: 1.5rem;
            }
            
            .card-body {
                padding: 20px 15px;
            }
            
            .btn-navigation {
                padding: 12px 25px;
            }
            
            .control-btn {
                width: 45px;
                height: 45px;
            }
        }

        /* Tema toggle específico */
        .theme-toggle {
            position: relative;
            overflow: hidden;
        }

        .theme-toggle i {
            transition: transform 0.3s ease;
        }

        .theme-toggle:hover i {
            transform: rotate(15deg);
        }

        [data-theme="dark"] .theme-toggle .bi-sun-fill {
            display: inline-block;
        }

        [data-theme="dark"] .theme-toggle .bi-moon-fill {
            display: none;
        }

        .theme-toggle .bi-sun-fill {
            display: none;
        }

        .theme-toggle .bi-moon-fill {
            display: inline-block;
        }

        [data-theme="dark"] .theme-toggle .bi-sun-fill {
            display: inline-block;
        }

        [data-theme="dark"] .theme-toggle .bi-moon-fill {
            display: none;
        }

        /* Resumo de respostas */
        .summary-box {
            background: var(--option-bg);
            border: 1px solid var(--border-color);
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .summary-box p {
            margin-bottom: 8px;
            color: var(--text-secondary);
        }

        .summary-box strong {
            color: var(--accent);
        }

        /* Pulse animation */
        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 107, 53, 0.4);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(255, 107, 53, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(255, 107, 53, 0);
            }
        }

        /* Spin animation */
        .spin {
            animation: spin 1s linear infinite;
            display: inline-block;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Slide animations */
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
    </style>
</head>
<body class="font-medium" id="mainBody">
    <!-- Painel de Controles -->
    <div class="controls-panel">
        <!-- Toggle Tema -->
        <button class="control-btn theme-toggle" onclick="toggleTheme()" title="Alternar tema">
            <i class="bi bi-moon-fill"></i>
            <i class="bi bi-sun-fill"></i>
        </button>
        
        <!-- Controles de Fonte -->
        <div class="font-controls">
            <button class="font-btn" onclick="changeFontSize('small')" title="Diminuir fonte">A-</button>
            <button class="font-btn active" onclick="changeFontSize('medium')" title="Fonte normal">A</button>
            <button class="font-btn" onclick="changeFontSize('large')" title="Aumentar fonte">A+</button>
        </div>
    </div>
    
    <div class="container-custom">
        <div class="card">
            <div class="card-header text-center">
                <h2><i class="bi bi-clipboard-heart-fill"></i> Anamnese Digital</h2>
                <p class="mb-0">Vamos conhecer melhor sua história</p>
            </div>
            
            <div class="card-body">
                <!-- Progress Bar -->
                <div class="progress">
                    <div class="progress-bar" id="progressBar" role="progressbar" style="width: 0%"></div>
                </div>
                
                <!-- Step Counter -->
                <div class="step-indicator">
                    <span id="currentStep"><i class="bi bi-arrow-right-circle-fill" style="color: var(--accent);"></i> Passo 1</span>
                    <span id="totalSteps">de 10</span>
                </div>
                
                <!-- Wizard Form -->
                <form id="wizardForm">
                    <input type="hidden" id="session_id" name="session_id" value="{{ $sessionId }}">
                    <input type="hidden" id="current_step" name="step" value="1">
                    @csrf
                    
                    <!-- Step 1: Dados Pessoais -->
                    <div class="question-card" data-step="1">
                        <h4 class="mb-4" style="color: var(--accent);"><i class="bi bi-calendar-heart-fill"></i> Dados Pessoais</h4>
                        <div class="mb-4">
                            <label class="form-label">📅 Qual a sua data de nascimento?</label>
                            <input type="date" class="form-control" name="nascimento" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">⚥ Qual o seu sexo?</label>
                            <select class="form-select" name="sexo" required>
                                <option value="">Selecione uma opção...</option>
                                <option value="feminino">Feminino</option>
                                <option value="masculino">Masculino</option>
                                <option value="outro">Outro</option>
                                <option value="prefiro_nao_dizer">Prefiro não dizer</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">🌸 Você está na menopausa?</label>
                            <select class="form-select" name="menopausa" required>
                                <option value="">Selecione uma opção...</option>
                                <option value="sim">Sim</option>
                                <option value="nao">Não</option>
                                <option value="nao_aplica">Não se aplica</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Step 2: Objetivos -->
                    <div class="question-card" data-step="2" style="display: none;">
                        <h4 class="mb-4" style="color: var(--accent);"><i class="bi bi-trophy-fill"></i> Objetivos</h4>
                        <div class="mb-4">
                            <label class="form-label">🎯 Qual é o seu objetivo ao contratar a Personal?</label>
                            <textarea class="form-control" name="objetivo" rows="3" placeholder="Ex: Emagrecer, ganhar massa muscular, melhorar condicionamento..." required></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">⚖️ Quantos quilos de gordura você deseja eliminar?</label>
                            <input type="number" step="0.1" class="form-control" name="gordura_eliminar" placeholder="Ex: 5, 10, 15..." required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">🔄 O que te impediu de alcançar seu objetivo antes?</label>
                            <textarea class="form-control" name="impedimentos" rows="3" placeholder="Ex: Falta de disciplina, tempo, lesões..." required></textarea>
                        </div>
                    </div>
                    
                    <!-- Step 3: Saúde -->
                    <div class="question-card" data-step="3" style="display: none;">
                        <h4 class="mb-4" style="color: var(--accent);"><i class="bi bi-heart-pulse-fill"></i> Saúde</h4>
                        <div class="mb-4">
                            <label class="form-label">🩺 Você tem alguma lesão ou histórico de lesão?</label>
                            <select class="form-select" name="lesao" required>
                                <option value="">Selecione uma opção...</option>
                                <option value="sim">Sim</option>
                                <option value="nao">Não</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">📋 Fale mais sobre suas lesões/condicionamento:</label>
                            <textarea class="form-control" name="detalhes_lesao" rows="4" placeholder="Descreva detalhadamente qualquer lesão ou condição de saúde relevante..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">💊 Toma algum medicamento? Se sim, qual?</label>
                            <textarea class="form-control" name="medicamentos" rows="2" placeholder="Liste todos os medicamentos que você toma atualmente..." required></textarea>
                        </div>
                    </div>
                    
                    <!-- Step 4: Hábitos -->
                    <div class="question-card" data-step="4" style="display: none;">
                        <h4 class="mb-4" style="color: var(--accent);"><i class="bi bi-egg-fried"></i> Hábitos</h4>
                        <div class="mb-4">
                            <label class="form-label">🍽️ Quantas refeições você faz por dia?</label>
                            <input type="number" class="form-control" name="refeicoes" min="1" max="10" placeholder="Ex: 5, 6 refeições" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">⏱️ Quanto tempo por dia se compromete a treinar?</label>
                            <select class="form-select" name="tempo_treino" required>
                                <option value="">Selecione uma opção...</option>
                                <option value="30min">30 minutos</option>
                                <option value="45min">45 minutos</option>
                                <option value="1h">1 hora</option>
                                <option value="1h30">1 hora e 30 minutos</option>
                                <option value="2h">2 horas</option>
                                <option value="mais">Mais de 2 horas</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">🕐 Que horas planeja treinar?</label>
                            <input type="time" class="form-control" name="horario_treino" required>
                        </div>
                    </div>
                    
                    <!-- Step 5: Local do Treino -->
                    <div class="question-card" data-step="5" style="display: none;">
                        <h4 class="mb-4" style="color: var(--accent);"><i class="bi bi-house-heart-fill"></i> Local do Treino</h4>
                        <div class="mb-4">
                            <label class="form-label">🏠 Seu treino será em casa ou na academia?</label>
                            <select class="form-select" name="local_treino" required>
                                <option value="">Selecione uma opção...</option>
                                <option value="casa">Em casa</option>
                                <option value="academia">Na academia</option>
                                <option value="ambos">Ambos</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">🚗 Quanto tempo leva para ir e voltar da academia?</label>
                            <select class="form-select" name="tempo_deslocamento">
                                <option value="">Selecione uma opção...</option>
                                <option value="15min">Até 15 minutos</option>
                                <option value="30min">15-30 minutos</option>
                                <option value="1h">30-60 minutos</option>
                                <option value="mais">Mais de 1 hora</option>
                            </select>
                            <small class="text-muted">Se treinar em casa, pode pular esta pergunta.</small>
                        </div>
                    </div>
                    
                    <!-- Step 6: Experiência -->
                    <div class="question-card" data-step="6" style="display: none;">
                        <h4 class="mb-4" style="color: var(--accent);"><i class="bi bi-graph-up-arrow"></i> Experiência</h4>
                        <div class="mb-4">
                            <label class="form-label">👤 Já fez acompanhamento com Personal Trainer?</label>
                            <select class="form-select" name="experiencia_personal" required>
                                <option value="">Selecione uma opção...</option>
                                <option value="sim">Sim</option>
                                <option value="nao">Não</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">💪 Qual seu nível de intimidade com exercícios?</label>
                            <select class="form-select" name="nivel_exercicios" required>
                                <option value="">Selecione uma opção...</option>
                                <option value="iniciante">Iniciante - Pouca ou nenhuma experiência</option>
                                <option value="intermediario">Intermediário - Já treinou antes</option>
                                <option value="avancado">Avançado - Treina regularmente</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Step 7: Medidas -->
                    <div class="question-card" data-step="7" style="display: none;">
                        <h4 class="mb-4" style="color: var(--accent);"><i class="bi bi-rulers"></i> Medidas Corporais</h4>
                        <div class="measurement-input">
                            <label class="form-label">⚖️ Peso atual (kg)</label>
                            <input type="number" step="0.1" class="form-control" name="peso" placeholder="Ex: 70.5" required>
                        </div>
                        <div class="measurement-input">
                            <label class="form-label">📏 Altura (metros)</label>
                            <input type="number" step="0.01" class="form-control" name="altura" placeholder="Ex: 1.75" required>
                        </div>
                        <div class="measurement-input">
                            <label class="form-label">💪 Bíceps direito (cm)</label>
                            <input type="number" step="0.1" class="form-control" name="biceps" placeholder="Ex: 32.5" required>
                        </div>
                        <div class="measurement-input">
                            <label class="form-label">🦵 Coxa direita (cm)</label>
                            <input type="number" step="0.1" class="form-control" name="coxa" placeholder="Ex: 55.0" required>
                        </div>
                    </div>
                    
                    <!-- Step 8: Mais Medidas -->
                    <div class="question-card" data-step="8" style="display: none;">
                        <h4 class="mb-4" style="color: var(--accent);"><i class="bi bi-rulers"></i> Medidas Complementares</h4>
                        <div class="measurement-input">
                            <label class="form-label">👕 Busto/Peito (cm)</label>
                            <input type="number" step="0.1" class="form-control" name="busto" placeholder="Ex: 95.0" required>
                        </div>
                        <div class="measurement-input">
                            <label class="form-label">🧵 Cintura (cm)</label>
                            <input type="number" step="0.1" class="form-control" name="cintura" placeholder="Ex: 75.0" required>
                        </div>
                        <div class="measurement-input">
                            <label class="form-label">👖 Quadril (cm)</label>
                            <input type="number" step="0.1" class="form-control" name="quadril" placeholder="Ex: 100.0" required>
                        </div>
                    </div>
                    
                    <!-- Step 9: Referência -->
                    <div class="question-card" data-step="9" style="display: none;">
                        <h4 class="mb-4" style="color: var(--accent);"><i class="bi bi-star-fill"></i> Referência</h4>
                        <div class="mb-4">
                            <label class="form-label">📍 Onde você conheceu a Carol?</label>
                            <select class="form-select" name="conheceu" required>
                                <option value="">Selecione uma opção...</option>
                                <option value="instagram">📸 Instagram</option>
                                <option value="facebook">📘 Facebook</option>
                                <option value="indicacao">💬 Indicação de amigo/familiar</option>
                                <option value="google">🔍 Google</option>
                                <option value="whatsapp">💚 WhatsApp</option>
                                <option value="outro">✨ Outro</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">💌 Recado/comentário para a Carol:</label>
                            <textarea class="form-control" name="comentario" rows="4" placeholder="Deixe uma mensagem, dúvida ou comentário..."></textarea>
                        </div>
                    </div>
                    
                    <!-- Step 10: Finalização -->
                    <div class="question-card" data-step="10" style="display: none;">
                        <h4 class="mb-4 text-center" style="color: var(--accent);"><i class="bi bi-check-circle-fill"></i> Finalizar</h4>
                        <div class="text-center mb-4">
                            <i class="bi bi-emoji-smile" style="font-size: 5rem; color: var(--accent);"></i>
                            <h3 class="mt-3" style="color: var(--text-primary);">Quase lá!</h3>
                            <p class="text-muted">Revise suas respostas e finalize seu cadastro</p>
                        </div>
                        
                        <!-- Resumo rápido -->
                        <div class="summary-box">
                            <p class="mb-2"><i class="bi bi-check-circle-fill" style="color: var(--accent);"></i> <strong>Total de respostas:</strong> <span id="totalRespostas">0</span></p>
                            <p class="mb-0"><i class="bi bi-info-circle-fill" style="color: var(--accent);"></i> Você pode voltar e revisar qualquer passo.</p>
                        </div>
                        
                        <div class="d-grid gap-3">
                            <button type="button" class="btn btn-success btn-lg pulse" onclick="submitForm()">
                                <i class="bi bi-check-lg"></i> Finalizar Anamnese
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="goToStep(1)">
                                <i class="bi bi-pencil"></i> Revisar respostas
                            </button>
                        </div>
                    </div>
                    
                    <!-- Navigation Buttons -->
                    <div class="d-flex justify-content-between mt-4 pt-3">
                        <button type="button" class="btn-navigation" id="prevBtn" onclick="changeStep(-1)" disabled>
                            <i class="bi bi-arrow-left"></i> Anterior
                        </button>
                        <button type="button" class="btn-navigation" id="nextBtn" onclick="changeStep(1)">
                            Próximo <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Loading Modal -->
    <div class="modal fade" id="loadingModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <div class="spinner-border mb-3" style="color: var(--accent);" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                    <h5 style="color: var(--text-primary);">Salvando suas respostas...</h5>
                    <p class="text-muted mb-0">Por favor, aguarde.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <i class="bi bi-check-circle-fill" style="font-size: 4rem; color: var(--accent);"></i>
                    <h4 class="mt-3" style="color: var(--text-primary);">Anamnese concluída com sucesso!</h4>
                    <p class="text-muted">Obrigado por compartilhar suas informações. A Carol entrará em contato em breve.</p>
                    <hr>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success" onclick="window.location.reload()">
                            <i class="bi bi-arrow-repeat"></i> Nova Anamnese
                        </button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i> Fechar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        let currentStep = 1;
        const totalSteps = 10;
        let isSaving = false;
        let sessionId = document.getElementById('session_id').value;
        
        // ========== FUNÇÕES DE TEMA ==========
        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            // Atualizar ícone do botão (já feito pelo CSS)
        }
        
        function loadTheme() {
            const savedTheme = localStorage.getItem('theme');
            
            // Verificar preferência do sistema
            if (!savedTheme) {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                document.documentElement.setAttribute('data-theme', prefersDark ? 'dark' : 'light');
            } else {
                document.documentElement.setAttribute('data-theme', savedTheme);
            }
        }
        
        // ========== FUNÇÕES DE FONTE ==========
        function changeFontSize(size) {
            const body = document.getElementById('mainBody');
            body.classList.remove('font-small', 'font-medium', 'font-large');
            
            // Atualizar botões ativos
            document.querySelectorAll('.font-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            switch(size) {
                case 'small':
                    body.classList.add('font-small');
                    document.querySelector('.font-btn[onclick*="small"]').classList.add('active');
                    localStorage.setItem('fontSize', 'small');
                    break;
                case 'medium':
                    body.classList.add('font-medium');
                    document.querySelector('.font-btn[onclick*="medium"]').classList.add('active');
                    localStorage.setItem('fontSize', 'medium');
                    break;
                case 'large':
                    body.classList.add('font-large');
                    document.querySelector('.font-btn[onclick*="large"]').classList.add('active');
                    localStorage.setItem('fontSize', 'large');
                    break;
            }
        }
        
        // ========== FUNÇÕES DO WIZARD ==========
        function changeStep(direction) {
            const nextStep = currentStep + direction;
            
            if (nextStep >= 1 && nextStep <= totalSteps) {
                // Validar campo atual antes de avançar
                if (direction > 0 && !validateCurrentStep()) {
                    showNotification('Por favor, preencha todos os campos obrigatórios.', 'warning');
                    return;
                }
                
                // Salvar respostas do passo atual
                saveCurrentStep(function() {
                    // Esconder passo atual
                    document.querySelector(`[data-step="${currentStep}"]`).style.display = 'none';
                    
                    // Mostrar próximo passo
                    document.querySelector(`[data-step="${nextStep}"]`).style.display = 'block';
                    
                    currentStep = nextStep;
                    
                    // Atualizar UI
                    updateNavigation();
                    
                    // Se for o último passo, carregar resumo
                    if (currentStep === totalSteps) {
                        loadSummary();
                    }
                });
            }
        }
        
        function goToStep(step) {
            if (step >= 1 && step <= totalSteps && step !== currentStep) {
                saveCurrentStep(function() {
                    document.querySelector(`[data-step="${currentStep}"]`).style.display = 'none';
                    document.querySelector(`[data-step="${step}"]`).style.display = 'block';
                    currentStep = step;
                    updateNavigation();
                });
            }
        }
        
        function updateNavigation() {
            // Atualizar progress bar
            const progress = ((currentStep - 1) / (totalSteps - 1)) * 100;
            document.getElementById('progressBar').style.width = progress + '%';
            
            // Atualizar step counter
            document.getElementById('currentStep').innerHTML = `<i class="bi bi-arrow-right-circle-fill" style="color: var(--accent);"></i> Passo ${currentStep}`;
            document.getElementById('totalSteps').innerText = `de ${totalSteps}`;
            
            // Habilitar/desabilitar botões
            document.getElementById('prevBtn').disabled = currentStep === 1;
            
            if (currentStep === totalSteps) {
                document.getElementById('nextBtn').style.display = 'none';
            } else {
                document.getElementById('nextBtn').style.display = 'block';
            }
        }
        
        function validateCurrentStep() {
            const currentStepElement = document.querySelector(`[data-step="${currentStep}"]`);
            const requiredFields = currentStepElement.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                    
                    // Adicionar feedback visual
                    if (!field.nextElementSibling?.classList.contains('invalid-feedback')) {
                        const feedback = document.createElement('div');
                        feedback.className = 'invalid-feedback';
                        feedback.innerText = 'Este campo é obrigatório';
                        field.parentNode.appendChild(feedback);
                    }
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            return isValid;
        }
        
        function saveCurrentStep(callback) {
            if (isSaving) {
                if (callback) callback();
                return;
            }
            
            isSaving = true;
            
            const formData = new FormData(document.getElementById('wizardForm'));
            formData.append('step', currentStep);
            
            // Mostrar indicador de salvamento
            showSavingIndicator(true);
            
            fetch('/anamnese/store', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSavingIndicator(false, true);
                } else {
                    showSavingIndicator(false, false);
                    console.error('Erro ao salvar:', data.message);
                }
            })
            .catch(error => {
                showSavingIndicator(false, false);
                console.error('Erro:', error);
            })
            .finally(() => {
                isSaving = false;
                if (callback) callback();
            });
        }
        
        function showSavingIndicator(isSaving, success = null) {
            let indicator = document.getElementById('savingIndicator');
            
            if (!indicator) {
                indicator = document.createElement('div');
                indicator.id = 'savingIndicator';
                indicator.style.position = 'fixed';
                indicator.style.bottom = '20px';
                indicator.style.right = '20px';
                indicator.style.padding = '12px 24px';
                indicator.style.borderRadius = '30px';
                indicator.style.zIndex = '9999';
                indicator.style.transition = 'all 0.3s';
                indicator.style.fontWeight = '500';
                document.body.appendChild(indicator);
            }
            
            if (isSaving) {
                indicator.innerHTML = '<i class="bi bi-arrow-repeat spin" style="color: var(--accent);"></i> Salvando...';
            } else if (success === true) {
                indicator.innerHTML = '<i class="bi bi-check-circle-fill" style="color: var(--accent);"></i> Salvo com sucesso!';
                
                setTimeout(() => {
                    indicator.style.opacity = '0';
                    setTimeout(() => {
                        indicator.remove();
                    }, 300);
                }, 2000);
            } else if (success === false) {
                indicator.innerHTML = '<i class="bi bi-exclamation-circle-fill" style="color: var(--accent);"></i> Erro ao salvar';
                
                setTimeout(() => {
                    indicator.style.opacity = '0';
                    setTimeout(() => {
                        indicator.remove();
                    }, 300);
                }, 3000);
            }
        }
        
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `notification alert-${type}`;
            notification.style.position = 'fixed';
            notification.style.top = '20px';
            notification.style.right = '20px';
            notification.style.zIndex = '9999';
            notification.style.minWidth = '300px';
            notification.style.padding = '15px 20px';
            notification.style.borderRadius = '10px';
            notification.style.animation = 'slideIn 0.3s';
            
            let icon = type === 'warning' ? '⚠️' : type === 'danger' ? '❌' : 'ℹ️';
            notification.innerHTML = `${icon} ${message}`;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s';
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 3000);
        }
        
        function loadSummary() {
            // Contar quantas respostas foram salvas
            const totalFields = document.querySelectorAll('[name]').length;
            document.getElementById('totalRespostas').innerText = totalFields;
        }
        
        function submitForm() {
            if (!validateCurrentStep()) {
                showNotification('Por favor, preencha todos os campos obrigatórios.', 'warning');
                return;
            }
            
            // Mostrar loading
            const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
            loadingModal.show();
            
            const formData = new FormData(document.getElementById('wizardForm'));
            formData.append('completed', 'true');
            formData.append('step', 'final');
            
            fetch('/anamnese/store', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => response.json())
            .then(data => {
                loadingModal.hide();
                
                if (data.success) {
                    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    successModal.show();
                } else {
                    showNotification('Erro ao finalizar: ' + data.message, 'danger');
                }
            })
            .catch(error => {
                loadingModal.hide();
                showNotification('Erro ao conectar com o servidor', 'danger');
                console.error(error);
            });
        }
        
        // ========== INICIALIZAÇÃO ==========
        document.addEventListener('DOMContentLoaded', function() {
            // Carregar tema salvo
            loadTheme();
            
            // Carregar preferência de fonte
            const savedFont = localStorage.getItem('fontSize');
            if (savedFont) {
                changeFontSize(savedFont);
            }
            
            // Esconder todos os steps exceto o primeiro
            for (let i = 2; i <= totalSteps; i++) {
                const stepElement = document.querySelector(`[data-step="${i}"]`);
                if (stepElement) {
                    stepElement.style.display = 'none';
                }
            }
            
            updateNavigation();
            
            // Adicionar listeners para validação em tempo real
            document.querySelectorAll('[required]').forEach(field => {
                field.addEventListener('input', function() {
                    if (this.value.trim()) {
                        this.classList.remove('is-invalid');
                    }
                });
                
                field.addEventListener('blur', function() {
                    validateCurrentStep();
                });
            });
        });
        
        // Auto-save a cada 30 segundos
        setInterval(() => {
            if (currentStep < totalSteps && !isSaving) {
                saveCurrentStep();
            }
        }, 30000);
        
        // Salvar ao sair da página
        window.addEventListener('beforeunload', function() {
            if (currentStep < totalSteps && !isSaving) {
                saveCurrentStep();
            }
        });
        
        // Observar mudanças no tema para atualizar ícones
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'data-theme') {
                    // Tema mudou
                    console.log('Tema alterado para:', mutation.target.getAttribute('data-theme'));
                }
            });
        });
        
        observer.observe(document.documentElement, { attributes: true });
    </script>
</body>
</html>