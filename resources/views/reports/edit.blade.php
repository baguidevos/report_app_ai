@extends('layouts.app')

@section('title', 'Modifier le Rapport - AI ReportHub')

@push('scripts')
    @vite(['resources/js/editor.js'])
    <script>
        window.reportId = '{{ $report->id }}';
    </script>

@endpush

@section('content')
<div class="space-y-6 animate-fade-in">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('reports.index') }}" 
           class="inline-flex items-center px-3 py-2 glass-effect border border-accent text-text-primary rounded-card hover:bg-accent/20 transition-smooth">
            <i class="fas fa-chevron-left"></i>
        </a>
        <div>
            <h1 class="text-xl font-semibold text-text-primary">Modifier le Rapport</h1>
            <p class="text-sm text-text-secondary mt-1">Dernière modification: {{ $report->updated_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('reports.update', $report) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Title -->
        <div>
            <label for="title" class="block text-sm font-medium text-text-secondary mb-2">Titre du rapport</label>
            <input type="text"
                   name="title"
                   id="title"
                   required
                   value="{{ old('title', $report->title) }}"
                   class="w-full px-4 py-3 glass-effect border border-accent rounded-card shadow-sm focus:border-primary focus:ring-primary bg-white @error('title') border-red-500 @enderror"
                   placeholder="Ex: Rapport quotidien - 15 Avril 2026">
            @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Frequency -->
        <div>
            <label for="frequency" class="block text-sm font-medium text-text-secondary mb-2">Fréquence</label>
            <select name="frequency"
                    id="frequency"
                    required
                    class="w-full px-4 py-3 glass-effect border border-accent rounded-card shadow-sm focus:border-primary focus:ring-primary bg-white @error('frequency') border-red-500 @enderror">
                <option value="">Sélectionnez une fréquence</option>
                <option value="daily" {{ old('frequency', $report->frequency) == 'daily' ? 'selected' : '' }}>Quotidien</option>
                <option value="weekly" {{ old('frequency', $report->frequency) == 'weekly' ? 'selected' : '' }}>Hebdomadaire</option>
                <option value="monthly" {{ old('frequency', $report->frequency) == 'monthly' ? 'selected' : '' }}>Mensuel</option>
            </select>
            @error('frequency')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- AI Summary Section -->
        <div class="glass-effect rounded-card p-6 border border-accent">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-text-primary">Résumé par IA</h2>
                    <p class="text-sm text-text-secondary">Générez une synthèse intelligente de votre rapport</p>
                </div>
                <button type="submit"
                        form="summarize-form"
                        id="summarize-report-btn"
                        class="inline-flex items-center px-6 py-3 bg-secondary  rounded-card font-medium btn-secondary-hover transition-smooth shadow-lg shadow-secondary/20">
                    <i class="fas fa-robot mr-2"></i> Résumer par IA
                </button>
            </div>

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-50/50 border border-green-200 text-green-600 rounded-card text-sm">
                    <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-3 bg-red-50/50 border border-red-200 text-red-600 rounded-card text-sm">
                    <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
                </div>
            @endif

            <div id="ai-summary-container" class="{{ $report->aiSummaries->isEmpty() ? 'hidden' : '' }}">
                <div class="p-5 glass-effect rounded-card border border-primary/20 bg-primary/5">
                    @php
                        $latestSummary = $report->aiSummaries->last();
                    @endphp
                    <div id="ai-summary-content" class="prose prose-slate max-w-none text-text-primary text-sm leading-relaxed">
                        @if($latestSummary)
                            {!! Str::markdown($latestSummary->body) !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Editor Toolbar -->
        <div>
            <label for="content" class="block text-sm font-medium text-text-secondary mb-2">Contenu</label>
            <div class="glass-effect rounded-card border border-accent overflow-hidden">
                <!-- Toolbar -->
                <div class="px-4 py-3 border-b border-accent flex gap-4">
                    <i class="fas fa-bold text-text-secondary cursor-pointer hover:text-text-primary transition-smooth"></i>
                    <i class="fas fa-italic text-text-secondary cursor-pointer hover:text-text-primary transition-smooth"></i>
                    <i class="fas fa-list-ul text-text-secondary cursor-pointer hover:text-text-primary transition-smooth"></i>
                    <i class="fas fa-heading text-text-secondary cursor-pointer hover:text-text-primary transition-smooth"></i>
                    <i class="fas fa-link text-text-secondary cursor-pointer hover:text-text-primary transition-smooth"></i>
                    <i class="fas fa-image text-text-secondary cursor-pointer hover:text-text-primary transition-smooth"></i>
                </div>

                <!-- Textarea -->
                <textarea name="content"
                          id="content"
                          required
                          rows="20"
                          class="w-full px-6 py-4 bg-transparent border-none outline-none resize-none text-text-primary @error('content') border-red-500 @enderror">{{ old('content', $report->content) }}</textarea>
            </div>
            @error('content')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Actions -->
        <div class="flex justify-between items-center pt-6 border-t border-accent">
            <a href="{{ route('reports.index') }}" 
               class="inline-flex items-center px-6 py-3 glass-effect border border-accent text-text-primary rounded-card font-medium hover:bg-accent/20 transition-smooth">
                Annuler
            </a>
            <button type="submit" 
                    class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-card font-medium btn-primary-hover transition-smooth">
                <i class="fas fa-check mr-2"></i>
                Mettre à Jour
            </button>
        </div>
    </form>

    @if($report->isMaster())
    <!-- Master Report Info -->
    <div class="glass-effect rounded-card p-6 border border-accent">
        <h2 class="text-lg font-semibold text-text-primary mb-3">Rapports Sources</h2>
        <p class="text-sm text-text-secondary mb-4">Ce rapport maître a été généré à partir de {{ $report->children->count() }} rapport(s) source(s).</p>

        <div class="space-y-3">
            @foreach($report->getChildren() as $child)
                <div class="flex items-center justify-between p-4 glass-effect rounded-card border border-accent">
                    <div>
                        <p class="text-sm font-medium text-text-primary">{{ $child->title }}</p>
                        <p class="text-xs text-text-secondary">{{ $child->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <span class="text-xs px-3 py-1 rounded-full bg-accent text-text-primary">
                        @if($child->frequency === 'daily')
                            Quotidien
                        @elseif($child->frequency === 'weekly')
                            Hebdomadaire
                        @elseif($child->frequency === 'monthly')
                            Mensuel
                        @endif
                    </span>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<form id="summarize-form" action="{{ route('reports.summarize', $report) }}" method="POST" class="hidden">
    @csrf
</form>
@endsection
