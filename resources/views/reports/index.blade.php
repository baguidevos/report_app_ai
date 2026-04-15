@extends('layouts.app')

@section('title', 'Mes Rapports - AI ReportHub')

@section('content')
<div class="space-y-6 animate-fade-in">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-text-primary">Mes Rapports</h1>
            <p class="text-sm text-text-secondary mt-1">{{ $reports->total() }} rapport{{ $reports->total() > 1 ? 's' : '' }} au total</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('reports.merge.form') }}" 
               class="inline-flex items-center px-4 py-3 glass-effect border border-accent text-text-primary rounded-card font-medium hover:bg-accent/20 transition-smooth">
                <i class="fas fa-object-group mr-2"></i>
                Fusionner
            </a>
            <a href="{{ route('reports.create') }}" 
               class="inline-flex items-center px-4 py-3 bg-primary text-white rounded-card font-medium btn-primary-hover transition-smooth">
                <i class="fas fa-plus mr-2"></i>
                Nouveau
            </a>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="flex items-center px-4 py-3 glass-effect rounded-card border border-accent mb-6">
        <i class="fas fa-search text-text-secondary mr-3"></i>
        <input type="text" 
               id="search-input"
               placeholder="Rechercher un rapport par titre..." 
               class="flex-1 bg-transparent border-none outline-none text-sm text-text-primary">
    </div>

    <!-- Filters -->
    <div class="glass-effect rounded-card p-4 border border-accent mb-6">
        <form method="GET" action="{{ route('reports.index') }}" class="flex items-center space-x-4">
            <div class="flex-1">
                <label for="frequency" class="block text-sm font-medium text-text-secondary mb-2">Filtrer par fréquence</label>
                <select name="frequency" 
                        id="frequency" 
                        onchange="this.form.submit()" 
                        class="w-full px-4 py-2 border-accent rounded-card shadow-sm focus:border-primary focus:ring-primary bg-white">
                    <option value="">Tous les rapports</option>
                    <option value="daily" {{ request('frequency') == 'daily' ? 'selected' : '' }}>Quotidien</option>
                    <option value="weekly" {{ request('frequency') == 'weekly' ? 'selected' : '' }}>Hebdomadaire</option>
                    <option value="monthly" {{ request('frequency') == 'monthly' ? 'selected' : '' }}>Mensuel</option>
                </select>
            </div>
            @if(request('frequency'))
                <div class="pt-6">
                    <a href="{{ route('reports.index') }}" 
                       class="text-sm text-primary hover:text-primary-hover font-medium transition-smooth">
                        <i class="fas fa-times mr-1"></i> Réinitialiser
                    </a>
                </div>
            @endif
            <div class="pt-6">
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-primary/10 text-primary text-sm font-medium">
                    <i class="fas fa-filter mr-1"></i>
                    {{ $reports->count() }} résultat{{ $reports->count() > 1 ? 's' : '' }}
                </span>
            </div>
        </form>
    </div>

    <!-- Reports Grid -->
    @if($reports->count() > 0)
        <div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="reports-grid">
                @foreach($reports as $index => $report)
                    <div class="report-card glass-effect rounded-card p-5 card-hover cursor-pointer border border-transparent hover:border-primary/30 animate-fade-in"
                         onclick="window.location.href='{{ route('reports.edit', $report) }}'"
                         data-title="{{ strtolower($report->title) }}"
                         style="animation-delay: {{ $index * 0.05 }}s">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <span class="font-semibold text-text-primary block mb-1 text-lg">{{ $report->title }}</span>
                                <span class="text-xs text-text-secondary">
                                    <i class="fas fa-clock mr-1"></i>{{ $report->created_at->diffForHumans() }}
                                </span>
                                <span class="text-xs text-text-secondary ml-3">
                                    <i class="fas fa-font mr-1"></i>{{ str_word_count($report->content) }} mots
                                </span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-4 pt-3 border-t border-accent">
                            <span class="text-xs px-3 py-1 rounded-full font-medium
                                {{ $report->frequency === 'daily' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $report->frequency === 'weekly' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $report->frequency === 'monthly' ? 'bg-purple-100 text-purple-700' : '' }}">
                                @if($report->frequency === 'daily')
                                    <i class="fas fa-calendar-day mr-1"></i>Quotidien
                                @elseif($report->frequency === 'weekly')
                                    <i class="fas fa-calendar-week mr-1"></i>Hebdomadaire
                                @elseif($report->frequency === 'monthly')
                                    <i class="fas fa-calendar-alt mr-1"></i>Mensuel
                                @endif
                            </span>
                            <div class="flex space-x-2">
                                <a href="{{ route('reports.edit', $report) }}" 
                                   class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-primary/10 text-primary hover:text-primary transition-smooth"
                                   onclick="event.stopPropagation()"
                                   title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('reports.destroy', $report) }}" 
                                      method="POST"
                                      onclick="event.stopPropagation()"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce rapport ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-red-100 text-red-500 hover:text-red-700 transition-smooth"
                                            title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $reports->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="glass-effect rounded-card p-12 text-center border border-accent">
            <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-primary/20 to-primary-hover/20 rounded-full flex items-center justify-center">
                <i class="fas fa-file-alt text-primary text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-text-primary mb-2">Aucun rapport trouvé</h3>
            <p class="text-sm text-text-secondary mb-6">
                @if(request('frequency'))
                    Aucun rapport avec cette fréquence. Essayez un autre filtre.
                @else
                    Commencez par créer votre premier rapport.
                @endif
            </p>
            <a href="{{ route('reports.create') }}" 
               class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-card font-medium btn-primary-hover transition-smooth">
                <i class="fas fa-plus mr-2"></i>
                Nouveau Rapport
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input');
        const reportsGrid = document.getElementById('reports-grid');
        
        if (searchInput && reportsGrid) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const cards = reportsGrid.querySelectorAll('.report-card');
                
                cards.forEach(card => {
                    const title = card.getAttribute('data-title');
                    if (title.includes(searchTerm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endpush
