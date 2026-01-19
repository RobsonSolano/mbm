<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MBM Climatização - Excelência em Ar Condicionado</title>
    <meta name="description" content="Manutenção, instalação e assistência técnica especializada em ar condicionado. Atendimento profissional e soluções completas para sua climatização.">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?php echo base_url('assets/img/favicon.svg') ?>">
    <link rel="icon" type="image/png" href="<?php echo base_url('assets/img/favicon.png') ?>">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/tailwind.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/landing-custom.css') ?>">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="font-sans antialiased bg-white">
    
    <!-- Header/Navigation -->
    <nav class="fixed w-full bg-white/95 backdrop-blur-sm shadow-sm z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0">
                    <a href="<?php echo base_url() ?>" class="flex items-center">
                        <img src="<?php echo base_url('assets/img/logotipo.svg') ?>" 
                             alt="MBM Climatização" 
                             width="150" height="50">
                    </a>
                </div>
                
                <div class="hidden md:flex space-x-8">
                    <a href="#servicos" class="text-gray-700 hover:text-primary-600 font-medium transition-colors">Serviços</a>
                    <a href="#beneficios" class="text-gray-700 hover:text-primary-600 font-medium transition-colors">Benefícios</a>
                    <a href="#marcas" class="text-gray-700 hover:text-primary-600 font-medium transition-colors">Marcas</a>
                    <a href="#faq" class="text-gray-700 hover:text-primary-600 font-medium transition-colors">FAQ</a>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="https://web.whatsapp.com/send?phone=5511949676793" target="_blank" 
                       class="hidden md:inline-flex items-center px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-full transition-all shadow-md hover:shadow-lg">
                        <i class="fab fa-whatsapp mr-2"></i> WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-gradient pt-32 pb-20 lg:pt-40 lg:pb-32 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-20 left-10 w-72 h-72 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="text-white animate-fade-in">
                    <div class="inline-block px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full text-sm font-medium mb-6">
                        <i class="fas fa-shield-alt mr-2"></i> Mais de 2 anos de experiência
                    </div>
                    
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-black leading-tight mb-6">
                        Climatização Profissional para Seu <span class="text-primary-200">Conforto Total</span>
                    </h1>
                    
                    <p class="text-xl text-primary-50 mb-8 leading-relaxed">
                        Especialistas em manutenção, instalação e assistência técnica de ar condicionado. 
                        Soluções completas com garantia de qualidade e atendimento ágil.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 mb-8">
                        <button type="button" onclick="document.getElementById('modalSolicitacao').classList.remove('hidden')"
                                class="inline-flex items-center justify-center px-8 py-4 bg-white text-primary-700 font-bold rounded-full hover:bg-primary-50 transition-all shadow-xl hover:shadow-2xl hover:scale-105">
                            <i class="fas fa-calendar-check mr-2"></i> Solicitar Atendimento
                        </button>
                        
                        <a href="https://web.whatsapp.com/send?phone=5511949676793" target="_blank"
                           class="inline-flex items-center justify-center px-8 py-4 bg-green-500 text-white font-bold rounded-full hover:bg-green-600 transition-all shadow-xl hover:shadow-2xl hover:scale-105">
                            <i class="fab fa-whatsapp mr-2 text-xl"></i> (11) 94967-6793
                        </a>
                    </div>
                    
                    <div class="flex items-center space-x-6 text-sm text-primary-100">
                        <div class="flex items-center">
                            <i class="fas fa-star text-yellow-400 mr-1"></i>
                            <i class="fas fa-star text-yellow-400 mr-1"></i>
                            <i class="fas fa-star text-yellow-400 mr-1"></i>
                            <i class="fas fa-star text-yellow-400 mr-1"></i>
                            <i class="fas fa-star text-yellow-400 mr-2"></i>
                            <span>4.9/5 Avaliação</span>
                        </div>
                        <div class="border-l border-primary-400 h-4"></div>
                        <div>
                            <i class="fas fa-users mr-2"></i> +500 Clientes Satisfeitos
                        </div>
                    </div>
                </div>
                
                <div class="hidden lg:block animate-slide-up">
                    <div class="grid grid-cols-1 gap-4">
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all">
                            <div class="flex items-center space-x-4">
                                <div class="bg-white/20 rounded-xl p-4">
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-1">Qualidade Garantida</h3>
                                    <p class="text-primary-100">Certificações e normas técnicas</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all">
                            <div class="flex items-center space-x-4">
                                <div class="bg-white/20 rounded-xl p-4">
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-1">Atendimento Rápido</h3>
                                    <p class="text-primary-100">Resposta em até 2 horas</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all">
                            <div class="flex items-center space-x-4">
                                <div class="bg-white/20 rounded-xl p-4">
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-1">Preço Justo</h3>
                                    <p class="text-primary-100">Orçamento sem compromisso</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-12 bg-gray-50 border-y border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="stat-number">10+</div>
                    <p class="text-gray-600 font-medium mt-2">Anos de Experiência</p>
                </div>
                <div class="text-center">
                    <div class="stat-number">500+</div>
                    <p class="text-gray-600 font-medium mt-2">Clientes Atendidos</p>
                </div>
                <div class="text-center">
                    <div class="stat-number">98%</div>
                    <p class="text-gray-600 font-medium mt-2">Satisfação</p>
                </div>
                <div class="text-center">
                    <div class="stat-number">24h</div>
                    <p class="text-gray-600 font-medium mt-2">Suporte Disponível</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="servicos" class="py-20 lg:py-32 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-black text-gray-900 mb-4">
                    Nossos <span class="text-primary-600">Serviços</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Soluções completas em climatização para residências, comércios e indústrias
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Manutenção Preventiva -->
                <div class="service-card bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                    <div class="icon-wrapper bg-primary-100 w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-tools text-3xl text-primary-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Manutenção Preventiva</h3>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        Garanta o melhor desempenho e aumente a vida útil do seu equipamento com nossa manutenção especializada.
                    </p>
                    <button onclick="document.getElementById('modalSolicitacao').classList.remove('hidden')" 
                            class="text-primary-600 font-semibold hover:text-primary-700 inline-flex items-center">
                        Saiba mais <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
                
                <!-- Instalação -->
                <div class="service-card bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                    <div class="icon-wrapper bg-green-100 w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-check-circle text-3xl text-green-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Instalação Profissional</h3>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        Instalação completa seguindo todas as normas técnicas e garantindo máxima eficiência energética.
                    </p>
                    <button onclick="document.getElementById('modalSolicitacao').classList.remove('hidden')" 
                            class="text-primary-600 font-semibold hover:text-primary-700 inline-flex items-center">
                        Saiba mais <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
                
                <!-- Assistência Técnica -->
                <div class="service-card bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                    <div class="icon-wrapper bg-orange-100 w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-headset text-3xl text-orange-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Assistência Técnica</h3>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        Diagnóstico preciso e reparo rápido para todos os tipos e marcas de ar condicionado.
                    </p>
                    <button onclick="document.getElementById('modalSolicitacao').classList.remove('hidden')" 
                            class="text-primary-600 font-semibold hover:text-primary-700 inline-flex items-center">
                        Saiba mais <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
                
                <!-- Refrigeração Comercial -->
                <div class="service-card bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                    <div class="icon-wrapper bg-blue-100 w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-snowflake text-3xl text-blue-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Refrigeração Industrial</h3>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        Soluções completas para câmaras frias, geladeiras e freezers comerciais e industriais.
                    </p>
                    <button onclick="document.getElementById('modalSolicitacao').classList.remove('hidden')" 
                            class="text-primary-600 font-semibold hover:text-primary-700 inline-flex items-center">
                        Saiba mais <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
                
                <!-- Eficiência Energética -->
                <div class="service-card bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                    <div class="icon-wrapper bg-yellow-100 w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-bolt text-3xl text-yellow-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Eficiência Energética</h3>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        Otimização do consumo de energia com tecnologias modernas e sustentáveis.
                    </p>
                    <button onclick="document.getElementById('modalSolicitacao').classList.remove('hidden')" 
                            class="text-primary-600 font-semibold hover:text-primary-700 inline-flex items-center">
                        Saiba mais <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
                
                <!-- Consultoria -->
                <div class="service-card bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                    <div class="icon-wrapper bg-purple-100 w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-chart-line text-3xl text-purple-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Consultoria Técnica</h3>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        Análise especializada para escolha e dimensionamento correto dos equipamentos.
                    </p>
                    <button onclick="document.getElementById('modalSolicitacao').classList.remove('hidden')" 
                            class="text-primary-600 font-semibold hover:text-primary-700 inline-flex items-center">
                        Saiba mais <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section id="beneficios" class="py-20 lg:py-32 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div>
                    <h2 class="text-4xl lg:text-5xl font-black text-gray-900 mb-6">
                        Por que escolher a <span class="text-primary-600">MBM?</span>
                    </h2>
                    <p class="text-xl text-gray-600 mb-8">
                        Profissionalismo, qualidade e compromisso com a satisfação do cliente em cada atendimento.
                    </p>
                    
                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div class="bg-primary-600 rounded-lg p-2 mt-1">
                                <i class="fas fa-check text-white"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900 mb-1">Técnicos Certificados</h4>
                                <p class="text-gray-600">Equipe altamente qualificada e treinada nas melhores práticas do mercado.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4">
                            <div class="bg-primary-600 rounded-lg p-2 mt-1">
                                <i class="fas fa-check text-white"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900 mb-1">Garantia de Serviço</h4>
                                <p class="text-gray-600">Todos os serviços possuem garantia e acompanhamento pós-atendimento.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4">
                            <div class="bg-primary-600 rounded-lg p-2 mt-1">
                                <i class="fas fa-check text-white"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900 mb-1">Peças Originais</h4>
                                <p class="text-gray-600">Utilizamos apenas peças originais e de fornecedores homologados.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4">
                            <div class="bg-primary-600 rounded-lg p-2 mt-1">
                                <i class="fas fa-check text-white"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900 mb-1">Atendimento Emergencial</h4>
                                <p class="text-gray-600">Suporte 24/7 para situações de emergência e urgência.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-4">
                        <div class="bg-white rounded-2xl p-6 shadow-lg">
                            <i class="fas fa-award text-4xl text-primary-600 mb-4"></i>
                            <h4 class="font-bold text-gray-900 text-lg">Certificações</h4>
                        </div>
                        <div class="bg-white rounded-2xl p-6 shadow-lg">
                            <i class="fas fa-leaf text-4xl text-green-600 mb-4"></i>
                            <h4 class="font-bold text-gray-900 text-lg">Sustentável</h4>
                        </div>
                    </div>
                    <div class="space-y-4 mt-8">
                        <div class="bg-white rounded-2xl p-6 shadow-lg">
                            <i class="fas fa-clock text-4xl text-orange-600 mb-4"></i>
                            <h4 class="font-bold text-gray-900 text-lg">Pontualidade</h4>
                        </div>
                        <div class="bg-white rounded-2xl p-6 shadow-lg">
                            <i class="fas fa-shield-alt text-4xl text-blue-600 mb-4"></i>
                            <h4 class="font-bold text-gray-900 text-lg">Segurança</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Brands Section -->
    <?php if (!empty($marcas)): ?>
    <section id="marcas" class="py-16 bg-white border-y border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h3 class="text-3xl font-bold text-gray-900 mb-2">Trabalhamos com as Melhores Marcas</h3>
                <p class="text-gray-600">Atendimento especializado para todas as marcas do mercado</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-8 items-center">
                <?php foreach ($marcas as $marca): ?>
                <div class="flex items-center justify-center p-4 grayscale hover:grayscale-0 transition-all">
                    <?php if (!empty($marca['logo'])): ?>
                        <img src="<?php echo base_url('writable/uploads/marcas/' . $marca['logo']) ?>" 
                             alt="<?php echo esc($marca['nome']) ?>" 
                             class="max-h-12 w-auto">
                    <?php else: ?>
                        <span class="text-gray-700 font-semibold"><?php echo esc($marca['nome']) ?></span>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- FAQ Section -->
    <section id="faq" class="py-20 lg:py-32 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-black text-gray-900 mb-4">
                    Perguntas <span class="text-primary-600">Frequentes</span>
                </h2>
                <p class="text-xl text-gray-600">
                    Tire suas dúvidas sobre nossos serviços
                </p>
            </div>
            
            <div class="space-y-4">
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <button class="w-full text-left px-6 py-5 font-semibold text-gray-900 hover:bg-gray-50 flex justify-between items-center" onclick="toggleFaq(this)">
                        <span>Com que frequência devo fazer manutenção no ar condicionado?</span>
                        <i class="fas fa-chevron-down transition-transform"></i>
                    </button>
                    <div class="faq-content hidden px-6 pb-5 text-gray-600">
                        Recomendamos manutenção preventiva a cada 6 meses para uso residencial e a cada 3 meses para uso comercial intenso. Isso garante eficiência, economia de energia e maior durabilidade do equipamento.
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <button class="w-full text-left px-6 py-5 font-semibold text-gray-900 hover:bg-gray-50 flex justify-between items-center" onclick="toggleFaq(this)">
                        <span>Quanto tempo leva uma instalação?</span>
                        <i class="fas fa-chevron-down transition-transform"></i>
                    </button>
                    <div class="faq-content hidden px-6 pb-5 text-gray-600">
                        Uma instalação residencial padrão leva de 4 a 6 horas. Para projetos maiores ou comerciais, realizamos uma avaliação prévia e informamos o prazo exato.
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <button class="w-full text-left px-6 py-5 font-semibold text-gray-900 hover:bg-gray-50 flex justify-between items-center" onclick="toggleFaq(this)">
                        <span>Vocês atendem urgências e emergências?</span>
                        <i class="fas fa-chevron-down transition-transform"></i>
                    </button>
                    <div class="faq-content hidden px-6 pb-5 text-gray-600">
                        Sim! Temos atendimento emergencial 24/7. Entre em contato pelo WhatsApp e responderemos o mais rápido possível.
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <button class="w-full text-left px-6 py-5 font-semibold text-gray-900 hover:bg-gray-50 flex justify-between items-center" onclick="toggleFaq(this)">
                        <span>Quais marcas vocês atendem?</span>
                        <i class="fas fa-chevron-down transition-transform"></i>
                    </button>
                    <div class="faq-content hidden px-6 pb-5 text-gray-600">
                        Atendemos todas as marcas do mercado: LG, Samsung, Electrolux, Carrier, Gree, Midea, Fujitsu, Daikin, Springer, Consul e muitas outras.
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <button class="w-full text-left px-6 py-5 font-semibold text-gray-900 hover:bg-gray-50 flex justify-between items-center" onclick="toggleFaq(this)">
                        <span>Os serviços têm garantia?</span>
                        <i class="fas fa-chevron-down transition-transform"></i>
                    </button>
                    <div class="faq-content hidden px-6 pb-5 text-gray-600">
                        Sim! Todos os nossos serviços possuem garantia. Instalações têm garantia de 90 dias e manutenções de 30 dias. Peças substituídas seguem a garantia do fabricante.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Final -->
    <section class="py-20 hero-gradient relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-20 w-64 h-64 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-20 w-80 h-80 bg-white rounded-full blur-3xl"></div>
        </div>
        
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative">
            <h2 class="text-4xl lg:text-5xl font-black text-white mb-6">
                Pronto para ter o melhor serviço de climatização?
            </h2>
            <p class="text-xl text-primary-50 mb-10">
                Entre em contato agora e receba um orçamento personalizado sem compromisso
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button type="button" onclick="document.getElementById('modalSolicitacao').classList.remove('hidden')"
                        class="inline-flex items-center justify-center px-10 py-5 bg-white text-primary-700 font-bold rounded-full hover:bg-primary-50 transition-all shadow-2xl hover:scale-105 text-lg">
                    <i class="fas fa-envelope mr-3"></i> Solicitar Orçamento
                </button>
                
                <a href="https://web.whatsapp.com/send?phone=5511949676793" target="_blank"
                   class="inline-flex items-center justify-center px-10 py-5 bg-green-500 text-white font-bold rounded-full hover:bg-green-600 transition-all shadow-2xl hover:scale-105 text-lg">
                    <i class="fab fa-whatsapp mr-3 text-2xl"></i> Falar no WhatsApp
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-8 mb-8">
                <div>
                    <img src="<?php echo base_url('assets/img/logotipo-white.svg') ?>" 
                         alt="MBM Climatização" 
                         class="h-10 w-auto mb-4">
                    <p class="text-gray-400 leading-relaxed">
                        Excelência em climatização com mais de 10 anos de experiência no mercado.
                    </p>
                </div>
                
                <div>
                    <h4 class="text-white font-semibold mb-4">Links Rápidos</h4>
                    <ul class="space-y-2">
                        <li><a href="#servicos" class="hover:text-primary-400 transition-colors">Serviços</a></li>
                        <li><a href="#beneficios" class="hover:text-primary-400 transition-colors">Benefícios</a></li>
                        <li><a href="#marcas" class="hover:text-primary-400 transition-colors">Marcas</a></li>
                        <li><a href="#faq" class="hover:text-primary-400 transition-colors">FAQ</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-white font-semibold mb-4">Contato</h4>
                    <ul class="space-y-2">
                        <li class="flex items-center">
                            <i class="fab fa-whatsapp mr-2 text-green-500"></i>
                            <span>(11) 94967-6793</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-2 text-primary-400"></i>
                            <span>contato@mbm.com.br</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-clock mr-2 text-yellow-500"></i>
                            <span>Seg-Sex: 8h-18h | Sáb: 8h-12h</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-8 text-center text-gray-500">
                <p>&copy; <?php echo date('Y') ?> MBM Climatização. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Modal Solicitação -->
    <div id="modalSolicitacao" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-2xl font-bold text-gray-900">Solicitar Atendimento</h3>
                    <button onclick="fecharModal()" 
                            class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Alert de Sucesso -->
            <div id="alertSucesso" class="hidden mx-6 mt-4 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                    <div>
                        <p class="font-semibold text-green-800">Solicitação enviada com sucesso!</p>
                        <p class="text-sm text-green-700">Em breve nossa equipe entrará em contato.</p>
                    </div>
                </div>
            </div>
            
            <!-- Alert de Erro -->
            <div id="alertErro" class="hidden mx-6 mt-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                    <div>
                        <p class="font-semibold text-red-800">Erro ao enviar solicitação!</p>
                        <p class="text-sm text-red-700" id="mensagemErro">Por favor, verifique os campos e tente novamente.</p>
                    </div>
                </div>
            </div>
            
            <form id="formSolicitacao" action="<?php echo base_url('novo/solicitar') ?>" method="POST" class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nome Completo *</label>
                        <input type="text" name="nome" id="nome" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">E-mail *</label>
                        <input type="email" name="email" id="email" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Celular/WhatsApp *</label>
                        <input type="tel" name="celular" id="celular" required 
                               placeholder="(11) 99999-9999"
                               maxlength="15"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Cidade *</label>
                        <input type="text" name="cidade" id="cidade" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Observação / Tipo de Serviço *</label>
                        <textarea name="observacao" id="observacao" rows="4" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all resize-none" 
                                  placeholder="Descreva o serviço necessário: manutenção, instalação, reparo, etc."></textarea>
                    </div>
                </div>
                
                <div class="mt-6 flex gap-3">
                    <button type="button" onclick="fecharModal()" 
                            class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-all">
                        Cancelar
                    </button>
                    <button type="submit" id="btnEnviar"
                            class="flex-1 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-all shadow-lg hover:shadow-xl">
                        <span id="btnTexto">Enviar Solicitação</span>
                        <span id="btnLoading" class="hidden">
                            <i class="fas fa-spinner fa-spin mr-2"></i> Enviando...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Máscara de telefone celular (limita a 11 dígitos: 2 DDD + 9 números)
        function mascaraTelefone(input) {
            let valor = input.value.replace(/\D/g, '');
            
            // Limita a 11 dígitos (2 DDD + 9 números do celular)
            if (valor.length > 11) {
                valor = valor.substring(0, 11);
            }
            
            // Aplica máscara apenas para celular (11 dígitos)
            if (valor.length <= 10) {
                // Telefone fixo: (11) 1234-5678
                valor = valor.replace(/^(\d{2})(\d{4})(\d{0,4})$/, function(match, ddd, parte1, parte2) {
                    if (parte2) {
                        return '(' + ddd + ') ' + parte1 + '-' + parte2;
                    }
                    return '(' + ddd + ') ' + parte1;
                });
            } else if (valor.length === 11) {
                // Celular: (11) 99999-9999
                valor = valor.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
            }
            
            input.value = valor;
        }
        
        document.getElementById('celular').addEventListener('input', function(e) {
            mascaraTelefone(e.target);
        });
        
        // Validação adicional ao perder o foco
        document.getElementById('celular').addEventListener('blur', function(e) {
            let valor = e.target.value.replace(/\D/g, '');
            if (valor.length > 0 && valor.length < 10) {
                e.target.setCustomValidity('Por favor, informe um número de celular válido (11 dígitos)');
            } else if (valor.length === 11 && valor[2] !== '9') {
                e.target.setCustomValidity('O celular deve começar com 9 após o DDD');
            } else {
                e.target.setCustomValidity('');
            }
        });
        
        // Feedback visual do formulário
        const formSolicitacao = document.getElementById('formSolicitacao');
        const btnEnviar = document.getElementById('btnEnviar');
        const btnTexto = document.getElementById('btnTexto');
        const btnLoading = document.getElementById('btnLoading');
        const alertSucesso = document.getElementById('alertSucesso');
        const alertErro = document.getElementById('alertErro');
        
        <?php if (session()->getFlashdata('flash_message')): ?>
            const flashMessage = <?php echo json_encode(session()->getFlashdata('flash_message')); ?>;
            
            // Abre o modal
            document.getElementById('modalSolicitacao').classList.remove('hidden');
            
            // Mostra o alerta apropriado
            if (flashMessage.tipo === 'success') {
                alertSucesso.classList.remove('hidden');
                formSolicitacao.classList.add('hidden');
                
                // Fecha automaticamente após 3 segundos
                setTimeout(function() {
                    fecharModal();
                    // Reseta o form para próxima vez
                    setTimeout(function() {
                        formSolicitacao.classList.remove('hidden');
                        alertSucesso.classList.add('hidden');
                        formSolicitacao.reset();
                    }, 500);
                }, 3000);
            } else {
                alertErro.classList.remove('hidden');
                document.getElementById('mensagemErro').innerHTML = flashMessage.mensagem;
                
                // Esconde o erro após 5 segundos
                setTimeout(function() {
                    alertErro.classList.add('hidden');
                }, 5000);
            }
        <?php endif; ?>
        
        function fecharModal() {
            document.getElementById('modalSolicitacao').classList.add('hidden');
            alertSucesso.classList.add('hidden');
            alertErro.classList.add('hidden');
            formSolicitacao.reset();
        }
        
        function toggleFaq(button) {
            const content = button.nextElementSibling;
            const icon = button.querySelector('i');
            
            content.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }
        
        // Close modal on outside click
        document.getElementById('modalSolicitacao').addEventListener('click', function(e) {
            if (e.target === this) {
                fecharModal();
            }
        });
        
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
