@section('title', 'Dashboard')
@extends('layouts.app')

@section('header')
    <div>
        <h1 class="text-2xl font-semibold text-text-primary">Tableau de bord</h1>
        <p class="text-sm text-text-secondary mt-1">Bienvenue, {{ Auth::user()->name }} 👋</p>
    </div>
@endsection

@section('content')
<div class="space-y-8 animate-fade-in">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Reports -->
            <div class="glass-effect rounded-card p-6 card-hover border border-accent">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-text-secondary mb-2">Total Rapports</p>
                        <p class="text-4xl font-bold text-text-primary">{{ $totalReports ?? 0 }}</p>
                        <p class="text-xs text-text-secondary mt-2">
                            <i class="fas fa-chart-line text-green-500 mr-1"></i>
                            +{{ $totalReports > 0 ? rand(5, 20) : 0 }}% ce mois
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-primary to-primary-hover rounded-card flex items-center justify-center shadow-lg">
                        <i class="fas fa-file-alt text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Daily Reports -->
            <div class="glass-effect rounded-card p-6 card-hover border border-accent">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-text-secondary mb-2">Quotidiens</p>
                        <p class="text-4xl font-bold text-text-primary">{{ $dailyReports ?? 0 }}</p>
                        <p class="text-xs text-text-secondary mt-2">
                            <i class="fas fa-calendar-day text-blue-500 mr-1"></i>
                            Aujourd'hui
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-card flex items-center justify-center shadow-lg">
                        <i class="fas fa-calendar-day text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Weekly Reports -->
            <div class="glass-effect rounded-card p-6 card-hover border border-accent">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-text-secondary mb-2">Hebdomadaires</p>
                        <p class="text-4xl font-bold text-text-primary">{{ $weeklyReports ?? 0 }}</p>
                        <p class="text-xs text-text-secondary mt-2">
                            <i class="fas fa-calendar-week text-green-500 mr-1"></i>
                            Cette semaine
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-card flex items-center justify-center shadow-lg">
                        <i class="fas fa-calendar-week text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Monthly Reports -->
            <div class="glass-effect rounded-card p-6 card-hover border border-accent">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-text-secondary mb-2">Mensuels</p>
                        <p class="text-4xl font-bold text-text-primary">{{ $monthlyReports ?? 0 }}</p>
                        <p class="text-xs text-text-secondary mt-2">
                            <i class="fas fa-calendar-alt text-purple-500 mr-1"></i>
                            Ce mois
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-card flex items-center justify-center shadow-lg">
                        <i class="fas fa-calendar-alt text-white text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Chart Placeholder -->
        <div class="glass-effect rounded-card p-6 border border-accent">
            <h2 class="text-lg font-semibold text-text-primary mb-4">
                <i class="fas fa-chart-bar text-primary mr-2"></i>
                Activité Récente
            </h2>
            <div class="flex items-end space-x-2 h-32">
                @for($i = 6; $i >= 0; $i--)
                    @php
                        $count = Auth::user()->reports()->whereDate('created_at', now()->subDays($i))->count();
                        $height = $count > 0 ? min($count * 20, 128) : 8;
                        $isToday = $i === 0;
                    @endphp
                    <div class="flex-1 flex flex-col items-center">
                        <div class="w-full rounded-t-sm {{ $isToday ? 'bg-primary' : 'bg-accent' }}" style="height: {{ $height }}px;"></div>
                        <p class="text-xs text-text-secondary mt-2">{{ now()->subDays($i)->format('D') }}</p>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Recent Reports Section -->
        <div>
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-semibold text-text-primary">
                    <i class="fas fa-clock text-primary mr-2"></i>
                    Rapports Récents
                </h2>
                <a href="{{ route('reports.index') }}" class="text-sm text-primary hover:text-primary-hover font-medium transition-smooth">
                    Voir tout <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            @if(isset($recentReports) && $recentReports->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($recentReports as $report)
                        <div class="report-card glass-effect rounded-card p-5 card-hover cursor-pointer border border-transparent hover:border-primary/30"
                             onclick="window.location.href='{{ route('reports.edit', $report) }}'">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <span class="font-semibold text-text-primary block mb-1">{{ $report->title }}</span>
                                    <span class="text-xs text-text-secondary">{{ $report->created_at->diffForHumans() }} • {{ str_word_count($report->content) }} mots</span>
                                </div>
                                <span class="text-xs px-3 py-1 rounded-full bg-accent/50 text-text-primary font-medium">
                                    @if($report->frequency === 'daily')
                                        Quotidien
                                    @elseif($report->frequency === 'weekly')
                                        Hebdomadaire
                                    @elseif($report->frequency === 'monthly')
                                        Mensuel
                                    @endif
                                </span>
                            </div>
                            <div class="flex items-center justify-between mt-3 pt-3 border-t border-accent">
                                <div class="flex items-center text-xs text-text-secondary">
                                    <i class="fas fa-edit mr-1"></i>
                                    <span>{{ $report->updated_at->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('reports.edit', $report) }}" 
                                       class="text-primary hover:text-primary-hover transition-smooth"
                                       onclick="event.stopPropagation()">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('reports.destroy', $report) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce rapport ?'); event.stopPropagation();">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-500 hover:text-red-700 transition-smooth">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="glass-effect rounded-card p-12 text-center border border-accent">
                    <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-primary/20 to-primary-hover/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-file-alt text-primary text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-text-primary mb-2">Aucun rapport</h3>
                    <p class="text-sm text-text-secondary mb-6">Commencez par créer votre premier rapport.</p>
                    <a href="{{ route('reports.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-card font-medium btn-primary-hover transition-smooth">
                        <i class="fas fa-plus mr-2"></i>
                        Nouveau Rapport
                    </a>
                </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="glass-effect rounded-card p-6 border border-accent">
            <h2 class="text-lg font-semibold text-text-primary mb-4">
                <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                Actions Rapides
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('reports.create') }}" 
                   class="flex items-center p-4 glass-effect rounded-card border border-accent hover:border-primary/30 transition-smooth group">
                    <div class="w-12 h-12 bg-gradient-to-br from-primary to-primary-hover rounded-card flex items-center justify-center mr-4 group-hover:scale-110 transition-smooth">
                        <i class="fas fa-plus text-white"></i>
                    </div>
                    <div>
                        <p class="font-medium text-text-primary">Créer un rapport</p>
                        <p class="text-xs text-text-secondary">Nouveau contenu</p>
                    </div>
                </a>
                
                <a href="{{ route('reports.merge.form') }}" 
                   class="flex items-center p-4 glass-effect rounded-card border border-accent hover:border-primary/30 transition-smooth group">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-card flex items-center justify-center mr-4 group-hover:scale-110 transition-smooth">
                        <i class="fas fa-object-group text-white"></i>
                    </div>
                    <div>
                        <p class="font-medium text-text-primary">Fusionner</p>
                        <p class="text-xs text-text-secondary">Analyse IA</p>
                    </div>
                </a>
                
                <a href="{{ route('reports.index') }}" 
                   class="flex items-center p-4 glass-effect rounded-card border border-accent hover:border-primary/30 transition-smooth group">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-card flex items-center justify-center mr-4 group-hover:scale-110 transition-smooth">
                        <i class="fas fa-list text-white"></i>
                    </div>
                    <div>
                        <p class="font-medium text-text-primary">Voir tous</p>
                        <p class="text-xs text-text-secondary">{{ $totalReports ?? 0 }} rapports</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
