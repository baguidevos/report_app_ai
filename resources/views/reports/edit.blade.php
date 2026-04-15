@extends('layouts.app')

@section('title', 'Modifier le Rapport - AI ReportHub')

@push('scripts')
    @vite(['resources/js/editor.js'])
    <script>
        window.reportId = '{{ $report->id }}';
    </script>
    <script>
        // Agent execution functionality
        document.addEventListener('DOMContentLoaded', function() {
            const executeAgentBtn = document.getElementById('execute-agent-btn');
            const agentSelect = document.getElementById('agent-select');
            const statusDiv = document.getElementById('agent-status');

            if (executeAgentBtn && agentSelect) {
                executeAgentBtn.addEventListener('click', async function() {
                    const agentId = agentSelect.value;
                    if (!agentId) {
                        alert('Veuillez sélectionner un agent');
                        return;
                    }

                    // Show loading state
                    executeAgentBtn.disabled = true;
                    executeAgentBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Traitement...';
                    statusDiv.classList.remove('hidden');
                    statusDiv.innerHTML = '<p class="text-sm text-primary"><div class="inline-block w-3 h-3 bg-primary rounded-full animate-pulse-dot mr-2"></div>Traitement en cours...</p>';

                    try {
                        const response = await fetch(`/agents/${agentId}/execute`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ report_id: {{ $report->id }} })
                        });

                        const data = await response.json();

                        if (data.success) {
                            statusDiv.innerHTML = '<p class="text-sm text-green-600"><i class="fas fa-check-circle mr-1"></i> Traitement terminé! Rechargez la page pour voir le nouveau rapport.</p>';

                            // Reload after 3 seconds
                            setTimeout(() => {
                                window.location.reload();
                            }, 3000);
                        } else {
                            statusDiv.innerHTML = '<p class="text-sm text-red-600">Erreur: ' + data.message + '</p>';
                        }
                    } catch (error) {
                        statusDiv.innerHTML = '<p class="text-sm text-red-600">Erreur de connexion</p>';
                    } finally {
                        executeAgentBtn.disabled = false;
                        executeAgentBtn.innerHTML = '<i class="fas fa-magic mr-2"></i> Exécuter l\'Agent';
                    }
                });
            }
        });
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

        <!-- AI Agent Section -->
        <div class="glass-effect rounded-card p-6 border border-accent">
            <h2 class="text-lg font-semibold text-text-primary mb-2">Agents IA</h2>
            <p class="text-sm text-text-secondary mb-4">Utilisez un agent IA pour transformer votre rapport</p>

            <div class="flex items-end space-x-3">
                <div class="flex-1">
                    <label for="agent-select" class="block text-sm font-medium text-text-secondary mb-2">Sélectionner un agent</label>
                    <select id="agent-select" class="w-full px-4 py-3 glass-effect border border-accent rounded-card shadow-sm focus:border-primary focus:ring-primary bg-white">
                        <option value="">Choisissez un agent...</option>
                        @foreach(\App\Models\Agent::getDefaultAgents() as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="button"
                        id="execute-agent-btn"
                        class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-card font-medium btn-primary-hover transition-smooth whitespace-nowrap">
                    <i class="fas fa-magic mr-2"></i> Exécuter l'Agent
                </button>
            </div>

            <div id="agent-status" class="mt-3 hidden"></div>
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
@endsection
